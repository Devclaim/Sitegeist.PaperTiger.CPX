<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\Flow\Annotations as Flow;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\ComponentEngine\SlotComponent;
use PackageFactory\ComponentEngine\Util;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\CheckboxItem\CheckboxItemProps;
use Sitegeist\PaperTiger\CPX\Components\Label\LabelProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\Components\Field\RadioItem\RadioItemProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Form\FormComponentRegistry;
use Sitegeist\PaperTiger\CPX\NodeTypes\Form\FormComponents;

abstract class AbstractFieldRenderer implements ContentNodeRendererInterface
{
    #[Flow\Inject]
    protected FormComponentRegistry $formComponentRegistry;

    protected function createFieldContainerProps(NeosContext $context): FieldContainerProps
    {
        $identifier = $this->identifier($context);

        return FieldContainerProps::create(
            id: 'fieldcontainer_' . $identifier,
            label: $this->stringProperty($context->node, 'label'),
            inputId: 'field_' . $identifier,
            isRequired: $this->boolProperty($context->node, 'isRequired'),
        );
    }

    protected function identifier(NeosContext $context): string
    {
        return $this->stringProperty($context->node, 'name') ?? $context->node->aggregateId->value;
    }

    protected function nameOrIdentifier(NeosContext $context): string
    {
        return $this->stringProperty($context->node, 'name') ?? $context->node->aggregateId->value;
    }

    protected function stringProperty(Node $node, string $propertyName): ?string
    {
        $value = $node->getProperty($propertyName);

        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return $value !== '' ? $value : null;
        }

        if (is_scalar($value)) {
            return (string)$value;
        }

        return null;
    }

    protected function boolProperty(Node $node, string $propertyName): ?bool
    {
        $value = $node->getProperty($propertyName);

        return is_bool($value) ? $value : null;
    }

    protected function intProperty(Node $node, string $propertyName): ?int
    {
        $value = $node->getProperty($propertyName);

        return is_int($value) ? $value : null;
    }

    protected function formattedDateProperty(Node $node, string $propertyName, string $format = 'Y-m-d'): ?string
    {
        $value = $node->getProperty($propertyName);

        if ($value instanceof \DateTimeInterface) {
            return $value->format($format);
        }

        return null;
    }

    /**
     * @return list<array{label:string,value:string}>
     */
    protected function options(Node $node): array
    {
        $rawOptions = $node->getProperty('options');

        if (!is_array($rawOptions)) {
            return [];
        }

        $options = [];
        foreach ($rawOptions as $option) {
            if (!is_array($option)) {
                continue;
            }

            $label = $option['label'] ?? null;
            $value = $option['value'] ?? null;

            if (!is_scalar($label) || !is_scalar($value)) {
                continue;
            }

            $options[] = [
                'label' => (string)$label,
                'value' => (string)$value,
            ];
        }

        return $options;
    }

    protected function components(): FormComponents
    {
        return $this->formComponentRegistry->current();
    }

    /**
     * @param class-string $componentClass
     */
    protected function createComponent(string $componentClass, array $arguments): ComponentInterface
    {
        return $componentClass::create(...$arguments);
    }

    protected function renderDropdownOptions(array $options, bool $includeEmptyOption, ?string $emptyLabel): ComponentInterface|string|null
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

    protected function wrapField(NeosContext $context, ComponentInterface|string|null $content): ComponentInterface
    {
        $fieldContainer = $this->createFieldContainerProps($context);
        $labelComponentClass = $this->components()->labelComponent();
        $fieldContainerClass = $this->components()->fieldContainerComponent();

        return $this->createComponent(
            $fieldContainerClass,
            [
                'fieldContainer' => $fieldContainer,
                'label' => $this->createComponent(
                    $labelComponentClass,
                    [
                        'label' => LabelProps::create(
                            inputId: $fieldContainer->inputId,
                            label: $fieldContainer->label,
                            isRequired: $fieldContainer->isRequired,
                        ),
                    ],
                ),
                'content' => $content,
            ],
        );
    }

    protected function renderCheckboxOptions(array $options, string $name, ?bool $isRequired = null): ComponentInterface|string|null
    {
        $parts = [];
        $componentClass = $this->components()->checkboxItemComponent();

        foreach ($options as $option) {
            $parts[] = $this->createComponent(
                $componentClass,
                [
                    'option' => CheckboxItemProps::create(
                        name: $name . '[]',
                        value: $option['value'],
                        label: $option['label'],
                        isRequired: $isRequired,
                    ),
                ],
            );
        }

        return $parts === [] ? null : SlotComponent::list(...$parts);
    }

    protected function renderRadioOptions(array $options, string $name, ?bool $isRequired = null): ComponentInterface|string|null
    {
        $parts = [];
        $componentClass = $this->components()->radioItemComponent();

        foreach ($options as $option) {
            $parts[] = $this->createComponent(
                $componentClass,
                [
                    'option' => RadioItemProps::create(
                        name: $name,
                        value: $option['value'],
                        label: $option['label'],
                        isRequired: $isRequired,
                    ),
                ],
            );
        }

        return $parts === [] ? null : SlotComponent::list(...$parts);
    }
}
