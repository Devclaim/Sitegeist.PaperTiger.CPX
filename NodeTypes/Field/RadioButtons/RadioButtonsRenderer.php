<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\RadioButtons;

use PackageFactory\ComponentEngine\SlotComponent;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\OPGM\Domain\ObjectPropertyGraphMapper;
use Sitegeist\PaperTiger\CPX\Domain\PaperTigerFormState;
use Sitegeist\PaperTiger\CPX\Components\Field\RadioGroupField\RadioGroupField;
use Sitegeist\PaperTiger\CPX\Components\Field\RadioGroupField\RadioGroupFieldProps;
use Sitegeist\PaperTiger\CPX\Components\Field\RadioItem\RadioItemProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

final class RadioButtonsRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly FieldContainerFactory $fieldContainerFactory,
        private readonly FieldComponentFactory $fieldComponentFactory,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $formField = ObjectPropertyGraphMapper::map($context->node, $context->subgraph, RadioButtons::class);
        $formState = PaperTigerFormState::fromRequest($context->request);
        $fieldContainer = FieldContainerProps::create(
            id: 'fieldcontainer_' . $formField->name,
            label: $formField->label,
            inputId: 'field_' . $formField->name,
            isRequired: $formField->isRequired,
            hasErrors: $formState?->hasErrorsFor($formField->name),
        );

        return $this->fieldContainerFactory->create(
            $context,
            RadioGroupField::create(
                field: RadioGroupFieldProps::create(
                    fieldContainer: $fieldContainer,
                    name: $formField->name,
                    isRequired: $formField->isRequired,
                    customErrorMessageEnabled: $formField->customErrorMessageEnabled,
                    customErrorMessage: $formField->customErrorMessage,
                ),
                content: $this->renderRadioOptions(
                    $this->normalizeOptions(
                        $context->node->getProperty('options'),
                    ),
                    $formField->name,
                    $formState?->getStringValue($formField->name),
                    $formField->isRequired,
                    $formField->customErrorMessageEnabled,
                    $formField->customErrorMessage,
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
                static fn(mixed $option): array => [
                    'label' => is_array($option) && is_string($option['label'] ?? null) ? $option['label'] : '',
                    'value' => is_array($option) && is_string($option['value'] ?? null) ? $option['value'] : '',
                ],
                $options,
            ),
        );
    }

    private function renderRadioOptions(
        array $options,
        string $name,
        ?string $selectedValue,
        ?bool $isRequired = null,
        ?bool $customErrorMessageEnabled = null,
        ?string $customErrorMessage = null,
    ): ComponentInterface|string|null {
        $parts = [];

        foreach ($options as $option) {
            $parts[] = $this->fieldComponentFactory->createRadio(
                option: RadioItemProps::create(
                    name: $name,
                    value: $option['value'],
                    label: $option['label'],
                    isChecked: $selectedValue === $option['value'],
                    isRequired: $isRequired,
                    customErrorMessageEnabled: $customErrorMessageEnabled,
                    customErrorMessage: $customErrorMessage,
                ),
            );
        }

        return $parts === [] ? null : SlotComponent::list(...$parts);
    }
}
