<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\CheckBoxes;

use PackageFactory\ComponentEngine\SlotComponent;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\OPGM\Domain\ObjectPropertyGraphMapper;
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
        $checkboxes = ObjectPropertyGraphMapper::map($context->node, $context->subgraph, CheckBoxes::class);
        $formState = PaperTigerFormState::fromRequest($context->request);
        $fieldContainer = FieldContainerProps::create(
            id: 'fieldcontainer_' . $checkboxes->name,
            label: $context->nodes->getStringValue($context->node, 'label'),
            inputId: 'field_' . $checkboxes->name,
            isRequired: $checkboxes->isRequired,
            hasErrors: $formState?->hasErrorsFor($checkboxes->name),
        );

        return $this->fieldContainerFactory->create(
            $context,
            CheckboxGroupField::create(
                field: CheckboxGroupFieldProps::create(
                    fieldContainer: $fieldContainer,
                    name: $checkboxes->name,
                    isRequired: $checkboxes->isRequired,
                    customErrorMessageEnabled: $checkboxes->customErrorMessageEnabled,
                    customErrorMessage: $checkboxes->customErrorMessage,
                ),
                content: $this->renderCheckboxOptions(
                    $this->normalizeOptions(
                        $context->node->getProperty('options'),
                    ),
                    $checkboxes->name,
                    $formState?->getStringValues($checkboxes->name) ?? [],
                    $checkboxes->isRequired,
                    $checkboxes->customErrorMessageEnabled,
                    $checkboxes->customErrorMessage,
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
    ): ComponentInterface|string|null {
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
