<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Text\MultiLineTextFormField;

use Neos\Neos\NodeTypes\Document;
use Neos\Neos\NodeTypes\Site;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\PaperTigerFormState;
use Sitegeist\PaperTiger\CPX\Components\Field\TextareaField\TextareaFieldProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

/**
 * @implements ContentNodeRendererInterface<MultiLineTextFormField,Document,Site>
 */
final class MultiLineTextFormFieldRenderer implements ContentNodeRendererInterface
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
            $this->fieldComponentFactory->createTextarea(
                field: TextareaFieldProps::create(
                    fieldContainer: $fieldContainer,
                    name: $context->current->name,
                    value: $formState?->getStringValue($context->current->name),
                    placeholder: $context->current->placeholder,
                    isRequired: $context->current->isRequired,
                    lineNumber: $context->current->lineNumber,
                    minimumLength: $context->current->minimumLength,
                    maximumLength: $context->current->maximumLength,
                    customErrorMessageEnabled: $context->current->customErrorMessageEnabled,
                    customErrorMessage: $context->current->customErrorMessage,
                ),
            ),
        );
    }
}
