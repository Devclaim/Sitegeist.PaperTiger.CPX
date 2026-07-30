<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Dropdown;

use PackageFactory\ComponentEngine\SlotComponent;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\ComponentEngine\Util;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\OPGM\Domain\ObjectPropertyGraphMapper;
use Sitegeist\PaperTiger\CPX\Domain\PaperTigerFormState;
use Sitegeist\PaperTiger\CPX\Components\Field\SelectField\SelectFieldProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

final class DropdownRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly FieldContainerFactory $fieldContainerFactory,
        private readonly FieldComponentFactory $fieldComponentFactory,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $dropdown = ObjectPropertyGraphMapper::map($context->node, $context->subgraph, Dropdown::class);
        $formState = PaperTigerFormState::fromRequest($context->request);
        $fieldContainer = FieldContainerProps::create(
            id: 'fieldcontainer_' . $dropdown->name,
            label: $dropdown->label,
            inputId: 'field_' . $dropdown->name,
            isRequired: $dropdown->isRequired,
            hasErrors: $formState?->hasErrorsFor($dropdown->name),
        );

        return $this->fieldContainerFactory->create(
            $context,
            $this->fieldComponentFactory->createSelect(
                field: SelectFieldProps::create(
                    fieldContainer: $fieldContainer,
                    name: $dropdown->name,
                    isMultiple: $dropdown->isMultiple,
                    isRequired: $dropdown->isRequired,
                    emptyOptionEnabled: $dropdown->emptyOptionEnabled,
                    emptyLabel: $dropdown->emptyLabel,
                    customErrorMessageEnabled: $dropdown->customErrorMessageEnabled,
                    customErrorMessage: $dropdown->customErrorMessage,
                ),
                content: $this->renderDropdownOptions(
                    options: $this->normalizeOptions(
                        $context->node->getProperty('options'),
                    ),
                    selectedValues: $formState?->getStringValues($dropdown->name) ?? [],
                    includeEmptyOption: $dropdown->emptyOptionEnabled,
                    emptyLabel: $dropdown->emptyLabel,
                ),
            ),
        );
    }

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

    private function renderDropdownOptions(array $options, array $selectedValues, bool $includeEmptyOption, ?string $emptyLabel): ComponentInterface|string|null
    {
        $parts = [];

        if ($includeEmptyOption) {
            $parts[] = '<option value=""' . (in_array('', $selectedValues, true) ? ' selected' : '') . '>';
            $parts[] = $emptyLabel === null ? '' : Util::escapeRenderValue($emptyLabel);
            $parts[] = '</option>';
        }

        foreach ($options as $option) {
            $parts[] = '<option value="' . Util::escapeAttributeValue($option['value']) . '"' . (in_array($option['value'], $selectedValues, true) ? ' selected' : '') . '>';
            $parts[] = Util::escapeRenderValue($option['label']);
            $parts[] = '</option>';
        }

        return $parts === [] ? null : SlotComponent::list(...$parts);
    }
}
