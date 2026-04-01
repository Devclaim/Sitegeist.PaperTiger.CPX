<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Dropdown;

use PackageFactory\ComponentEngine\SlotComponent;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\ComponentEngine\Util;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\SelectField\SelectField;
use Sitegeist\PaperTiger\CPX\Components\Field\SelectField\SelectFieldProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

final class DropdownRenderer implements ContentNodeRendererInterface
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $name = $context->nodes->getStringValue($context->node, 'name') ?? $context->node->aggregateId->value;
        $fieldContainer = FieldContainerProps::create(
            id: 'fieldcontainer_' . $name,
            label: $context->nodes->getStringValue($context->node, 'label'),
            inputId: 'field_' . $name,
            isRequired: $context->nodes->getBoolValue($context->node, 'isRequired'),
        );

        return FieldContainerFactory::create(
            $context,
            SelectField::create(
                field: SelectFieldProps::create(
                    fieldContainer: $fieldContainer,
                    name: $name,
                    isMultiple: $context->nodes->getBoolValue($context->node, 'isMultiple'),
                    isRequired: $context->nodes->getBoolValue($context->node, 'isRequired'),
                    emptyOptionEnabled: $context->nodes->getBoolValue($context->node, 'emptyOptionEnabled'),
                    emptyLabel: $context->nodes->getStringValue($context->node, 'emptyLabel'),
                    customErrorMessageEnabled: $context->nodes->getBoolValue($context->node, 'customErrorMessageEnabled'),
                    customErrorMessage: $context->nodes->getStringValue($context->node, 'customErrorMessage'),
                ),
                content: $this->renderDropdownOptions(
                    options: $this->normalizeOptions(
                        $context->nodes->getArrayValue($context->node, 'options'),
                    ),
                    includeEmptyOption: $context->nodes->getBoolValue($context->node, 'emptyOptionEnabled') ?? false,
                    emptyLabel: $context->nodes->getStringValue($context->node, 'emptyLabel'),
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

    private function renderDropdownOptions(array $options, bool $includeEmptyOption, ?string $emptyLabel): ComponentInterface|string|null
    {
        $parts = [];

        if ($includeEmptyOption) {
            $parts[] = '<option value="">';
            $parts[] = $emptyLabel === null ? '' : Util::escapeRenderValue($emptyLabel);
            $parts[] = '</option>';
        }

        foreach ($options as $option) {
            $parts[] = '<option value="' . Util::escapeAttributeValue($option['value']) . '">';
            $parts[] = Util::escapeRenderValue($option['label']);
            $parts[] = '</option>';
        }

        return $parts === [] ? null : SlotComponent::list(...$parts);
    }
}
