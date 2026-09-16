<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\RadioButtonsFormField;

use Neos\Neos\NodeTypes\Document;
use Neos\Neos\NodeTypes\Site;
use PackageFactory\ComponentEngine\ComponentList;
use PackageFactory\ComponentEngine\SlotComponent;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\PaperTigerFormState;
use Sitegeist\PaperTiger\CPX\Components\Field\RadioGroupField\RadioGroupField;
use Sitegeist\PaperTiger\CPX\Components\Field\RadioGroupField\RadioGroupFieldProps;
use Sitegeist\PaperTiger\CPX\Components\Field\RadioItem\RadioItemProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

/**
 * @implements ContentNodeRendererInterface<RadioButtonsFormField,Document,Site>
 */
final class RadioButtonsFormFieldRenderer implements ContentNodeRendererInterface
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
            RadioGroupField::create(
                field: RadioGroupFieldProps::create(
                    fieldContainer: $fieldContainer,
                    name: $context->current->name,
                    isRequired: $context->current->isRequired,
                    customErrorMessageEnabled: $context->current->customErrorMessageEnabled,
                    customErrorMessage: $context->current->customErrorMessage,
                ),
                content: $this->renderRadioOptions(
                    $this->normalizeOptions(
                        /** @todo ¯\_(ツ)_/¯ */
                        $context->node->getProperty('options'),
                    ),
                    $context->current->name,
                    $formState?->getStringValue($context->current->name),
                    $context->current->isRequired,
                    $context->current->customErrorMessageEnabled,
                    $context->current->customErrorMessage,
                ),
            ),
        );
    }

    /**
     * @param array<mixed> $options
     * @return array<mixed>
     */
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

    /**
     * @param array<mixed> $options
     * @return ComponentList<ComponentInterface>|null
     */
    private function renderRadioOptions(
        array $options,
        string $name,
        ?string $selectedValue,
        ?bool $isRequired = null,
        ?bool $customErrorMessageEnabled = null,
        ?string $customErrorMessage = null,
    ): ComponentList|null {
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

        return $parts === [] ? null : ComponentList::list(...$parts);
    }
}
