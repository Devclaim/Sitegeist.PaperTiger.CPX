<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Form;

use Neos\ContentRepository\Core\SharedModel\Node\NodeAddress;
use PackageFactory\ComponentEngine\ComponentCollection;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\ComponentEngine\StringComponent;
use PackageFactory\Neos\ComponentEngine\Caching\CacheDirective;
use PackageFactory\Neos\ComponentEngine\Caching\CacheSegment;
use PackageFactory\Neos\ComponentEngine\Integration\ContentRenderer;
use PackageFactory\Neos\ComponentEngine\Integration\RenderingEntryPoint;
use PackageFactory\Neos\ComponentEngine\Integration\RenderingUseCase;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenField;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenFieldProps;
use Sitegeist\PaperTiger\CPX\Components\Error\ErrorProps;
use Sitegeist\PaperTiger\CPX\Components\Form\Form;
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

    public function create(NeosContext $context): ComponentInterface
    {
        $formMode = $this->formMode($context);
        if ($formMode === FormMode::FORM_MODE_STANDARD) {
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

    public function renderStandardForm(NeosContext $context): ComponentInterface
    {
        return $this->createForm($context);
    }

    private function createForm(NeosContext $context): ComponentInterface
    {
        $this->formSubmissionRequestProcessor->process($context);

        $isSuccess = $context->request->getInternalArgument(FormSubmissionRequestProcessor::REQUEST_ARGUMENT_SUCCESS) === true;
        if ($isSuccess) {
            $formId = $this->formId($context);

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

        return ComponentCollection::list(
            Form::create(
                form: $this->createFormProps($context, $context->renderingMode->isEdit),
                error: $this->renderGeneralError($context),
                content: ComponentCollection::list(
                    $this->createContextField($context, 'paperTigerNode', NodeAddress::fromNode($context->node)->toJson()),
                    $this->createContextField($context, 'paperTigerDocument', NodeAddress::fromNode($context->documentNode)->toJson()),
                    ...array_filter([$this->renderFields($context)]),
                    ...($this->formMode($context) === FormMode::FORM_MODE_ASYNC ? [
                        $this->renderAsyncValidationDescriptor($context),
                        $this->resourceFactory->publicScriptTag(
                            'Sitegeist.PaperTiger.CPX',
                            'Scripts/AsyncForm.js',
                        ),
                    ] : []),
                ),
            ),
            ...($context->renderingMode->isEdit ? array_filter([
                $this->renderMessageActionPreview($context),
                $this->resourceFactory->publicStylesheetTag(
                    'Sitegeist.PaperTiger.CPX',
                    'Styles/Backend.css',
                ),
                $this->resourceFactory->publicScriptTag(
                    'Sitegeist.PaperTiger.CPX',
                    'Scripts/Backend.js',
                ),
            ]) : []),
        );
    }

    private function renderAsyncValidationDescriptor(NeosContext $context): ComponentInterface
    {
        $formId = $this->formId($context);

        $messageTemplate = $this->fieldComponentFactory->createMessage(
            message: MessageProps::create(id: $formId),
            content: StringComponent::fromHtmlString('{content}'),
        );

        $errorTemplate = $this->fieldComponentFactory->createError(
            error: ErrorProps::create(message: '{content}'),
        );

        $descriptor = [
            'formId' => $formId,
            'fields' => $this->asyncValidationDescriptorFactory->forForm($context),
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

    private function renderGeneralError(NeosContext $context): ComponentInterface|string|null
    {
        $formState = PaperTigerFormState::fromRequest($context->request);
        if ($formState === null) {
            return null;
        }

        $generalErrors = $formState->getGeneralErrors();
        if ($generalErrors === []) {
            return null;
        }

        return ComponentCollection::list(...array_map(
            fn ($error) => $this->fieldComponentFactory->createError(
                error: ErrorProps::create(message: $error->message),
            ),
            $generalErrors
        ));
    }

    private function createFormProps(NeosContext $context, bool $forEditMode = false): FormProps
    {
        $formId = $this->formId($context);
        $formMode = $this->formMode($context);

        return FormProps::create(
            id: $formId,
            action: $forEditMode
                ? null
                : '#' . $formId,
            method: $forEditMode ? null : 'post',
            noValidate: $forEditMode ? null : ($formMode === FormMode::FORM_MODE_ASYNC),
            formMode: $forEditMode ? null : $formMode,
        );
    }

    private function renderFields(NeosContext $context): ?ComponentInterface
    {
        return $this->contentRenderer->renderContentChildren(
            $context,
            RenderingUseCase::CONTENT,
        );
    }

    private function renderMessageActionPreview(NeosContext $context): ?ComponentInterface
    {
        return MessageActionPreview::create(
            formId: $this->formId($context),
            content: $this->fieldComponentFactory->createMessage(
                message: MessageProps::create(id: $this->formId($context)),
                content: $context->neos->getEditable($context->node, 'message', true),
            )
        );
    }

    private function formId(NeosContext $context): string
    {
        return 'form_' . $context->node->aggregateId->value;
    }

    private function createContextField(NeosContext $context, string $name, string $value): HiddenField
    {
        return HiddenField::create(
            field: HiddenFieldProps::create(
                name: $name,
                value: $value,
                inBackend: false,
            ),
        );
    }

    private function formMode(NeosContext $context): FormMode
    {
        return $context->nodes->getObjectValue(
            $context->node,
            'formMode',
            FormMode::class
        ) ?: FormMode::FORM_MODE_STANDARD;
    }
}
