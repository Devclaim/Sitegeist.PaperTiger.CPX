<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Form;

use Neos\ContentRepository\Core\SharedModel\Node\NodeAddress;
use Neos\ContentRepository\Core\SharedModel\Node\NodeAggregateId;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Neos\NodeTypes\Document;
use Neos\Neos\NodeTypes\Site;
use PackageFactory\ComponentEngine\ComponentList;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\ComponentEngine\StringComponent;
use PackageFactory\Neos\ComponentEngine\Caching\CacheDirective;
use PackageFactory\Neos\ComponentEngine\Caching\CacheSegment;
use PackageFactory\Neos\ComponentEngine\Integration\ContentRenderer;
use PackageFactory\Neos\ComponentEngine\Integration\RenderingEntryPoint;
use PackageFactory\Neos\ComponentEngine\Integration\RenderingUseCase;
use PackageFactory\Neos\ComponentEngine\NeosAccessInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenField;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenFieldProps;
use Sitegeist\PaperTiger\CPX\Components\Error\ErrorProps;
use Sitegeist\PaperTiger\CPX\Components\Form\Form as FormComponent;
use Sitegeist\PaperTiger\CPX\Components\Form\FormMode;
use Sitegeist\PaperTiger\CPX\Components\Form\FormProps;
use Sitegeist\PaperTiger\CPX\Components\Message\MessageProps;
use Sitegeist\PaperTiger\CPX\Components\MessageActionPreview\MessageActionPreview;
use Sitegeist\PaperTiger\CPX\Domain\Action\MessageAction;
use Sitegeist\PaperTiger\CPX\Domain\AsyncValidationDescriptorFactory;
use Sitegeist\PaperTiger\CPX\Domain\FormSubmissionRequestProcessor;
use Sitegeist\PaperTiger\CPX\Domain\PaperTigerFormState;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Resource\ResourceFactory;

final class FormFactory
{
    public function __construct(
        private readonly ContentRenderer $contentRenderer,
        private readonly ResourceFactory $resourceFactory,
        private readonly FieldComponentFactory $fieldComponentFactory,
        private readonly FormSubmissionRequestProcessor $formSubmissionRequestProcessor,
        private readonly AsyncValidationDescriptorFactory $asyncValidationDescriptorFactory,
    ) {
    }

    /**
     * @param NeosContext<Form,Document,Site> $context
     */
    public function create(NeosContext $context): ComponentInterface
    {
        if ($context->current->formMode === FormMode::FORM_MODE_STANDARD) {
            return CacheSegment::create(
                cacheDirective: new CacheDirective(
                    cacheEntryId: 'uncached',
                    nodeId: $context->node->aggregateId,
                    documentId: $context->documentNode->aggregateId,
                    siteId: $context->siteNode->aggregateId,
                    nodeName: null,
                    entryPoint: RenderingEntryPoint::fromClassAndMethod(self::class, 'renderStandardForm'),
                ),
                content: $this->renderStandardForm($context),
            );
        }

        return $this->createForm($context);
    }

    /**
     * @param NeosContext<Form,Document,Site> $context
     */
    public function renderStandardForm(NeosContext $context): ComponentInterface
    {
        return $this->createForm($context);
    }

    /**
     * @param NeosContext<Form,Document,Site> $context
     */
    private function createForm(NeosContext $context): ComponentInterface
    {
        $this->formSubmissionRequestProcessor->process($context);

        $isSuccess = (bool)$context->request->getInternalArgument(FormSubmissionRequestProcessor::REQUEST_ARGUMENT_SUCCESS) === true;
        if ($isSuccess) {
            $formId = $this->formId($context->node->aggregateId);

            $message = $context->request->getInternalArgument(MessageAction::REQUEST_ARGUMENT_MESSAGE);
            if (is_string($message) && $message !== '') {
                return $this->fieldComponentFactory->createMessage(
                    message: MessageProps::create(id: $formId),
                    content: StringComponent::fromHtmlString($message),
                );
            }

            // No message action configured: still hide the form, but keep the anchor.
            return StringComponent::fromHtmlString('<a id="' . htmlspecialchars($formId, ENT_QUOTES) . '"></a>');
        }

        return ComponentList::list(
            FormComponent::create(
                form: $this->createFormProps($context->current, $context->renderingMode->isEdit),
                error: $this->renderGeneralError($context->request),
                content: ComponentList::list(
                    $this->createContextField('paperTigerNode', NodeAddress::fromNode($context->node)->toJson()),
                    $this->createContextField('paperTigerDocument', NodeAddress::fromNode($context->documentNode)->toJson()),
                    ...[$this->contentRenderer->renderContentChildren(
                        $context,
                        RenderingUseCase::CONTENT,
                    )],
                    ...($context->current->formMode === FormMode::FORM_MODE_ASYNC ? [
                        $this->renderAsyncValidationDescriptor($context->current),
                        $this->resourceFactory->publicScriptTag(
                            'Sitegeist.PaperTiger.CPX',
                            'Scripts/AsyncForm.js',
                        ),
                    ] : []),
                ),
            ),
            ...($context->renderingMode->isEdit ? [
                $this->renderMessageActionPreview($context->current, $context->neos),
                $this->resourceFactory->publicStylesheetTag(
                    'Sitegeist.PaperTiger.CPX',
                    'Styles/Backend.css',
                ),
                $this->resourceFactory->publicScriptTag(
                    'Sitegeist.PaperTiger.CPX',
                    'Scripts/Backend.js',
                ),
            ] : []),
        );
    }

    private function renderAsyncValidationDescriptor(Form $form): ComponentInterface
    {
        $formId = $this->formId($form->node->aggregateId);

        $messageTemplate = $this->fieldComponentFactory->createMessage(
            message: MessageProps::create(id: $formId),
            content: StringComponent::fromHtmlString('{content}'),
        );

        $errorTemplate = $this->fieldComponentFactory->createError(
            error: ErrorProps::create(message: '{content}'),
        );

        $descriptor = [
            'formId' => $formId,
            'fields' => $this->asyncValidationDescriptorFactory->forForm($form),
            'templates' => [
                'message' => $messageTemplate->render(),
                'error' => $errorTemplate->render(),
            ],
        ];

        $json = json_encode($descriptor, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
        // Prevent "</script>" from terminating the script tag if it ever appears in template HTML.
        $json = str_replace('</script', '<\\/script', $json);

        return StringComponent::fromHtmlString(
            sprintf(
                '<script type="application/json" data-papertiger-validation data-form-id="%s">%s</script>',
                htmlspecialchars($formId, ENT_QUOTES),
                $json,
            ),
        );
    }

    private function renderGeneralError(ActionRequest $request): ComponentInterface|null
    {
        $formState = PaperTigerFormState::fromRequest($request);
        if ($formState === null) {
            return null;
        }

        $generalErrors = $formState->getGeneralErrors();
        if ($generalErrors === []) {
            return null;
        }

        return ComponentList::list(...array_map(
            fn ($error) => $this->fieldComponentFactory->createError(
                error: ErrorProps::create(message: $error->message),
            ),
            $generalErrors
        ));
    }

    private function createFormProps(Form $form, bool $forEditMode = false): FormProps
    {
        $formId = $this->formId($form->node->aggregateId);

        return FormProps::create(
            id: $formId,
            action: $forEditMode
                ? null
                : '#' . $formId,
            method: $forEditMode ? null : 'post',
            noValidate: $forEditMode ? null : ($form->formMode === FormMode::FORM_MODE_ASYNC),
            formMode: $forEditMode ? null : $form->formMode,
        );
    }

    private function renderMessageActionPreview(Form $form, NeosAccessInterface $neosAccess): ComponentInterface
    {
        return MessageActionPreview::create(
            content: $this->fieldComponentFactory->createMessage(
                message: MessageProps::create(id: $this->formId($form->node->aggregateId)),
                content: $neosAccess->getEditableFromProperty($form->message, true),
            ),
            formId: $this->formId($form->node->aggregateId)
        );
    }

    private function formId(NodeAggregateId $nodeAggregateId): string
    {
        return 'form_' . $nodeAggregateId->value;
    }

    private function createContextField(string $name, string $value): HiddenField
    {
        return HiddenField::create(
            field: HiddenFieldProps::create(
                name: $name,
                value: $value,
                inBackend: false,
            ),
        );
    }
}
