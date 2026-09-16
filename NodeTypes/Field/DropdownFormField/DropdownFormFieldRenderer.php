<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\DropdownFormField;

use Neos\Neos\NodeTypes\Document;
use Neos\Neos\NodeTypes\Site;
use PackageFactory\ComponentEngine\ComponentList;
use PackageFactory\ComponentEngine\SlotComponent;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\ComponentEngine\Util;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\PaperTigerFormState;
use Sitegeist\PaperTiger\CPX\Components\Field\SelectField\SelectFieldProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

/**
 * @implements ContentNodeRendererInterface<DropdownFormField,Document,Site>
 */
final class DropdownFormFieldRenderer implements ContentNodeRendererInterface
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
            $this->fieldComponentFactory->createSelect(
                field: SelectFieldProps::create(
                    fieldContainer: $fieldContainer,
                    name: $context->current->name,
                    isMultiple: $context->current->isMultiple,
                    isRequired: $context->current->isRequired,
                    emptyOptionEnabled: $context->current->emptyOptionEnabled,
                    emptyLabel: $context->current->emptyLabel,
                    customErrorMessageEnabled: $context->current->customErrorMessageEnabled,
                    customErrorMessage: $context->current->customErrorMessage,
                ),
                content: $this->renderDropdownOptions(
                    options: $this->normalizeOptions(
                        /** @todo ¯\_(ツ)_/¯ */
                        $context->node->getProperty('options'),
                    ),
                    selectedValues: $formState?->getStringValues($context->current->name) ?? [],
                    includeEmptyOption: $context->current->emptyOptionEnabled,
                    emptyLabel: $context->current->emptyLabel,
                ),
            ),
        );
    }

    /**
     * @param array<mixed>|null $options
     * @return array<mixed>
     */
    private function normalizeOptions(?array $options): array
    {
        if ($options === null) {
            return [];
        }

        return array_values(
            array_map(
                static fn (mixed $option): array => [
                    'label' => is_array($option) && is_string($option['label'] ?? null) ? $option['label'] : '',
                    'value' => is_array($option) && is_string($option['value'] ?? null) ? $option['value'] : '',
                ],
                $options,
            ),
        );
    }

    /**
     * @param array<mixed> $options
     * @param array<mixed> $selectedValues
     * @return SlotComponent|null
     */
    private function renderDropdownOptions(array $options, array $selectedValues, bool $includeEmptyOption, ?string $emptyLabel): SlotComponent|null
    {
        $parts = [];

        if ($includeEmptyOption) {
            $parts[] = '<option value=""' . (in_array('', $selectedValues, true) ? ' selected' : '') . '>';
            $parts[] = $emptyLabel === null ? '' : Util::escapeText($emptyLabel);
            $parts[] = '</option>';
        }

        foreach ($options as $option) {
            $parts[] = '<option value="' . Util::escapeAttributeValue($option['value']) . '"' . (in_array($option['value'], $selectedValues, true) ? ' selected' : '') . '>';
            $parts[] = Util::escapeText($option['label']);
            $parts[] = '</option>';
        }

        return $parts === [] ? null : SlotComponent::list(...$parts);
    }
}
