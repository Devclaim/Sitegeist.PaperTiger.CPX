<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Form;

use Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindChildNodesFilter;
use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\ContentRepository\Core\SharedModel\Node\NodeAddress;
use Neos\ContentRepository\Core\SharedModel\Node\NodeName;
use Neos\Flow\I18n\Translator;
use PackageFactory\ComponentEngine\ComponentCollection;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\ComponentEngine\SlotComponent;
use PackageFactory\ComponentEngine\StringComponent;
use PackageFactory\Neos\ComponentEngine\Caching\CacheDirective;
use PackageFactory\Neos\ComponentEngine\Caching\CacheSegment;
use PackageFactory\Neos\ComponentEngine\Integration\ContentRenderer;
use PackageFactory\Neos\ComponentEngine\Integration\RenderingEntryPoint;
use PackageFactory\Neos\ComponentEngine\Integration\RenderingUseCase;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\Neos\ComponentEngine\Presentation\Component\ContentElementCollection;
use Sitegeist\PaperTiger\CPX\Components\FieldNames\FieldNames;
use Sitegeist\PaperTiger\CPX\Components\FieldNameToken\FieldNameToken;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenField;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenFieldProps;
use Sitegeist\PaperTiger\CPX\Components\Error\ErrorProps;
use Sitegeist\PaperTiger\CPX\Components\Form\Form;
use Sitegeist\PaperTiger\CPX\Components\Form\FormMode;
use Sitegeist\PaperTiger\CPX\Components\Form\FormProps;
use Sitegeist\PaperTiger\CPX\Components\FormEditor\FormEditor;
use Sitegeist\PaperTiger\CPX\Components\FormSectionHeader\FormSectionHeader;
use Sitegeist\PaperTiger\CPX\Components\Message\MessageProps;
use Sitegeist\PaperTiger\CPX\Domain\Action\MessageAction;
use Sitegeist\PaperTiger\CPX\Domain\FormSubmissionRequestProcessor;
use Sitegeist\PaperTiger\CPX\Domain\PaperTigerFormState;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Resource\ResourceFactory;

final class FormFactory
{
    public function __construct(
        private readonly ContentRenderer $contentRenderer,
        private readonly Translator $translator,
        private readonly ResourceFactory $resourceFactory,
        private readonly FieldComponentFactory $fieldComponentFactory,
        private readonly FormSubmissionRequestProcessor $formSubmissionRequestProcessor,
    ) {
    }

    public function create(NeosContext $context): ComponentInterface
    {
        if ($context->renderingMode->isEdit) {
            return $this->createEditor($context);
        }

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

        return Form::create(
            form: $this->createFormProps($context),
            error: $this->renderGeneralError($context),
            content: ComponentCollection::list(
                $this->createContextField($context, 'paperTigerNode', NodeAddress::fromNode($context->node)->toJson()),
                $this->createContextField($context, 'paperTigerDocument', NodeAddress::fromNode($context->documentNode)->toJson()),
                ...array_filter([$this->renderFields($context)]),
            ),
        );
    }

    private function createEditor(NeosContext $context): FormEditor
    {
        return FormEditor::create(
            assets: SlotComponent::list(
                ...array_filter([
                    $this->resourceFactory->inlinePublicStyle(
                        'Sitegeist.PaperTiger.CPX',
                        'Styles/Backend.css',
                    ),
                    $this->resourceFactory->inlinePublicScript(
                        'Sitegeist.PaperTiger.CPX',
                        'Scripts/Backend.js',
                    ),
                ]),
            ),
            fields: $this->renderFieldsEditor($context),
            actions: $this->renderActionsEditor($context),
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

        return FormProps::create(
            id: $formId,
            action: $forEditMode
                ? null
                : '#' . $formId,
            method: $forEditMode ? null : 'post',
        );
    }

    private function renderFields(NeosContext $context): ComponentInterface|string|null
    {
        if ($this->formMode($context) === FormMode::FORM_MODE_STANDARD) {
            return $this->renderStandardFields($context);
        }

        return $this->contentRenderer->forContentCollectionChildNode(
            node: $context->node,
            collectionName: NodeName::fromString('fields'),
            context: $context,
            additionalClasses: ['papertiger-form__fields']
        );
    }

    private function renderStandardFields(NeosContext $context): ?ComponentInterface
    {
        $collectionNode = $this->findCollectionNode($context, 'fields');
        if (!$collectionNode instanceof Node) {
            return null;
        }

        $items = $this->contentRenderer->renderContentChildren(
            $context->with(node: $collectionNode),
            RenderingUseCase::CONTENT,
        );

        return ContentElementCollection::create(
            editable: $context->renderingMode->isEdit,
            nodeAddress: NodeAddress::fromNode($collectionNode),
            fusionPath: RenderingEntryPoint::forUseCase(RenderingUseCase::CONTENT_COLLECTION)->serializeToString(),
            content: $items,
            additionalClasses: ['papertiger-form__fields'],
        );
    }

    private function renderFieldsEditor(NeosContext $context): ComponentInterface|string|null
    {
        return Form::create(
            form: $this->createFormProps($context, true),
            error: null,
            content: ComponentCollection::list(
                $this->createContextField($context, 'paperTigerNode', NodeAddress::fromNode($context->node)->toJson()),
                $this->createContextField($context, 'paperTigerDocument', NodeAddress::fromNode($context->documentNode)->toJson()),
                ...array_filter([
                    $this->createCollectionEditor(
                        context: $context,
                        collectionName: 'fields',
                        additionalClasses: ['papertiger-form__fields'],
                        content: fn (?ComponentInterface $items) => ComponentCollection::list(
                            FormSectionHeader::create(
                                number: '1',
                                title: $this->translate('form.formFields.header', 'Form fields'),
                            ),
                            ...($items ? [$items] : []),
                        ),
                    ),
                ]),
            ),
        );
    }

    private function renderActionsEditor(NeosContext $context): ComponentInterface|string|null
    {
        $fieldNames = $this->renderFieldNames($context);

        return $this->createCollectionEditor(
            context: $context,
            collectionName: 'actions',
            additionalClasses: ['papertiger-form__actions'],
            content: fn (?ComponentInterface $items) => ComponentCollection::list(
                FormSectionHeader::create(
                    number: '2',
                    title: $this->translate('form.followUpActions.header', 'Follow up actions'),
                ),
                ...($fieldNames ? [$fieldNames] : []),
                ...($items ? [$items] : []),
            ),
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
            ),
        );
    }

    private function renderFieldNames(NeosContext $context): ?ComponentInterface
    {
        $fieldsCollectionNode = $this->findCollectionNode($context, 'fields');
        if (!$fieldsCollectionNode instanceof Node) {
            return null;
        }

        $tokens = [];

        foreach ($context->subgraph->findChildNodes(
            $fieldsCollectionNode->aggregateId,
            FindChildNodesFilter::create(),
        ) as $fieldNode) {
            $name = $context->nodes->getStringValue($fieldNode, 'name');
            if ($name === null || $name === '') {
                continue;
            }

            $tokens[] = FieldNameToken::create(
                token: '{' . $name . '}',
                buttonTitle: $this->translate('actionCollection.fieldNames.copyToClipboard', 'Copy to clipboard'),
            );
        }

        if ($tokens === []) {
            return null;
        }

        return FieldNames::create(
            description: $this->translate('actionCollection.fieldNames.description', 'Field names:'),
            content: SlotComponent::list(...$tokens),
        );
    }

    private function findCollectionNode(NeosContext $context, string $collectionName): ?Node
    {
        $collectionNode = $context->subgraph->findNodeByPath(
            NodeName::fromString($collectionName),
            $context->node->aggregateId,
        );

        return $collectionNode instanceof Node ? $collectionNode : null;
    }

    private function createCollectionEditor(
        NeosContext $context,
        string $collectionName,
        array $additionalClasses,
        \Closure $content,
    ): ?ComponentInterface {
        $collectionNode = $this->findCollectionNode($context, $collectionName);
        if (!$collectionNode instanceof Node) {
            return null;
        }

        $items = $this->contentRenderer->renderContentChildren(
            $context->with(node: $collectionNode),
            RenderingUseCase::CONTENT,
        );

        return ContentElementCollection::create(
            editable: $context->renderingMode->isEdit,
            nodeAddress: NodeAddress::fromNode($collectionNode),
            fusionPath: RenderingEntryPoint::forUseCase(RenderingUseCase::CONTENT_COLLECTION)->serializeToString(),
            content: $content($items),
            additionalClasses: $additionalClasses,
        );
    }

    private function translate(string $id, string $fallback): string
    {
        return $this->translator->translateById(
            $id,
            [],
            null,
            null,
            'Main',
            'Sitegeist.PaperTiger.CPX',
        ) ?: $fallback;
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
