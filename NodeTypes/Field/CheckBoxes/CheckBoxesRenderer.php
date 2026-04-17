<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\CheckBoxes;

use PackageFactory\ComponentEngine\SlotComponent;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\PaperTigerFormState;
use Sitegeist\PaperTiger\CPX\Components\Field\CheckboxGroupField\CheckboxGroupField;
use Sitegeist\PaperTiger\CPX\Components\Field\CheckboxGroupField\CheckboxGroupFieldProps;
use Sitegeist\PaperTiger\CPX\Components\Field\CheckboxItem\CheckboxItemProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

final class CheckBoxesRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly FieldContainerFactory $fieldContainerFactory,
        private readonly FieldComponentFactory $fieldComponentFactory,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $name = $context->nodes->getStringValue($context->node, 'name') ?? $context->node->aggregateId->value;
        $formState = PaperTigerFormState::fromRequest($context->request);
        $isRequired = $context->nodes->getBoolValue($context->node, 'isRequired');
        $customErrorMessageEnabled = $context->nodes->getBoolValue($context->node, 'customErrorMessageEnabled');
        $customErrorMessage = $context->nodes->getStringValue($context->node, 'customErrorMessage');
        $fieldContainer = FieldContainerProps::create(
            id: 'fieldcontainer_' . $name,
            label: $context->nodes->getStringValue($context->node, 'label'),
            inputId: 'field_' . $name,
            isRequired: $isRequired,
            hasErrors: $formState?->hasErrorsFor($name),
        );

        return $this->fieldContainerFactory->create(
            $context,
            CheckboxGroupField::create(
                field: CheckboxGroupFieldProps::create(
                    fieldContainer: $fieldContainer,
                    name: $name,
                    isRequired: $isRequired,
                    customErrorMessageEnabled: $customErrorMessageEnabled,
                    customErrorMessage: $customErrorMessage,
                ),
                content: $this->renderCheckboxOptions(
                    $this->normalizeOptions(
                        $context->node->getProperty('options'),
                    ),
                    $name,
                    $formState?->getStringValues($name) ?? [],
                    $isRequired,
                    $customErrorMessageEnabled,
                    $customErrorMessage,
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

    private function renderCheckboxOptions(
        array $options,
        string $name,
        array $selectedValues,
        ?bool $isRequired = null,
        ?bool $customErrorMessageEnabled = null,
        ?string $customErrorMessage = null,
    ): ComponentInterface|string|null
    {
        $parts = [];

        foreach ($options as $option) {
            $parts[] = $this->fieldComponentFactory->createCheckbox(
                option: CheckboxItemProps::create(
                    name: $name . '[]',
                    value: $option['value'],
                    label: $option['label'],
                    isChecked: in_array($option['value'], $selectedValues, true),
                    isRequired: $isRequired,
                    customErrorMessageEnabled: $customErrorMessageEnabled,
                    customErrorMessage: $customErrorMessage,
                ),
            );
        }

        return $parts === [] ? null : SlotComponent::list(...$parts);
    }
}
