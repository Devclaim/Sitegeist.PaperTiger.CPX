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
use PackageFactory\Neos\ComponentEngine\Integration\ContentRenderer;
use PackageFactory\Neos\ComponentEngine\Integration\RenderingEntryPoint;
use PackageFactory\Neos\ComponentEngine\Integration\RenderingUseCase;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\Neos\ComponentEngine\Presentation\Component\ContentElementCollection;
use Sitegeist\PaperTiger\CPX\Components\FieldNames\FieldNames;
use Sitegeist\PaperTiger\CPX\Components\FieldNameToken\FieldNameToken;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenField;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenFieldProps;
use Sitegeist\PaperTiger\CPX\Components\Form\Form;
use Sitegeist\PaperTiger\CPX\Components\Form\FormProps;
use Sitegeist\PaperTiger\CPX\Components\FormEditor\FormEditor;
use Sitegeist\PaperTiger\CPX\Components\FormSectionHeader\FormSectionHeader;
use Sitegeist\PaperTiger\CPX\NodeTypes\Resource\ResourceFactory;

final class FormFactory
{
    public function __construct(
        private readonly ContentRenderer $contentRenderer,
        private readonly Translator $translator,
        private readonly ResourceFactory $resourceFactory,
    ) {
    }

    public function create(NeosContext $context): ComponentInterface
    {
        return $context->renderingMode->isEdit
            ? $this->createEditor($context)
            : $this->createForm($context);
    }

    private function createForm(NeosContext $context): Form
    {
        return Form::create(
            form: $this->createFormProps($context),
            content: ComponentCollection::list(
                $this->createNodeAggregateIdField($context),
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

    private function createFormProps(NeosContext $context, bool $forEditMode = false): FormProps
    {
        $formId = $this->formId($context);

        return FormProps::create(
            id: $formId,
            action: $forEditMode
                ? null
                : (string)$context->neos->getActionUri(
                    package: 'Sitegeist.PaperTiger.CPX',
                    controller: 'FormSubmission',
                    action: 'formData',
                ),
            method: $forEditMode ? null : 'post',
        );
    }

    private function renderFields(NeosContext $context): ComponentInterface|string|null
    {
        return $this->contentRenderer->forContentCollectionChildNode(
            node: $context->node,
            collectionName: NodeName::fromString('fields'),
            context: $context,
            additionalClasses: ['papertiger-form__fields']
        );
    }

    private function renderFieldsEditor(NeosContext $context): ComponentInterface|string|null
    {
        return Form::create(
            form: $this->createFormProps($context, true),
            content: ComponentCollection::list(
                $this->createNodeAggregateIdField($context),
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

    private function createNodeAggregateIdField(NeosContext $context): HiddenField
    {
        return HiddenField::create(
            field: HiddenFieldProps::create(
                name: 'nodeAggregateId',
                value: $context->node->aggregateId->value,
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
}
