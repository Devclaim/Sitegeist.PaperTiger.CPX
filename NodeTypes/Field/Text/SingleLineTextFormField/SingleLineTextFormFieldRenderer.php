<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Text\SingleLineTextFormField;

use Neos\Neos\NodeTypes\Document;
use Neos\Neos\NodeTypes\Site;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\PaperTigerFormState;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputFieldProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

/**
 * @implements ContentNodeRendererInterface<SingleLineTextFormField,Document,Site>
 */
final class SingleLineTextFormFieldRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly FieldContainerFactory $fieldContainerFactory,
        private readonly FieldComponentFactory $fieldComponentFactory,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $formState = PaperTigerFormState::fromRequest($context->request);
        $fieldContainer = FieldContainerProps::create(
            id: 'fieldcontainer_' . $context->current->name,
            label: $context->current->label,
            inputId: 'field_' . $context->current->name,
            isRequired: $context->current->isRequired,
            hasErrors: $formState?->hasErrorsFor($context->current->name),
        );

        return $this->fieldContainerFactory->create(
            $context,
            $this->fieldComponentFactory->createInput(
                field: InputFieldProps::create(
                    fieldContainer: $fieldContainer,
                    type: 'text',
                    name: $context->current->name,
                    value: $formState?->getStringValue($context->current->name),
                    placeholder: $context->current->placeholder,
                    isRequired: $context->current->isRequired,
                    minimumLength: $context->current->minimumLength !== null ? (string)$context->current->minimumLength : null,
                    maximumLength: $context->current->maximumLength !== null ? (string)$context->current->maximumLength : null,
                    regularExpression: $context->current->regularExpression,
                    step: null,
                    customErrorMessageEnabled: $context->current->customErrorMessageEnabled,
                    customErrorMessage: $context->current->customErrorMessage,
                ),
            ),
        );
    }
}
