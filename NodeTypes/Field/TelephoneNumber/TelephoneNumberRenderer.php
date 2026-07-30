<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\TelephoneNumber;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\OPGM\Domain\ObjectPropertyGraphMapper;
use Sitegeist\PaperTiger\CPX\Domain\PaperTigerFormState;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputFieldProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

final class TelephoneNumberRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly FieldContainerFactory $fieldContainerFactory,
        private readonly FieldComponentFactory $fieldComponentFactory,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $formField = ObjectPropertyGraphMapper::map($context->node, $context->subgraph, TelephoneNumber::class);
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
            $this->fieldComponentFactory->createInput(
                field: InputFieldProps::create(
                    fieldContainer: $fieldContainer,
                    type: 'tel',
                    name: $formField->name,
                    value: $formState?->getStringValue($formField->name),
                    placeholder: $formField->placeholder,
                    isRequired: $formField->isRequired,
                    minimumLength: null,
                    maximumLength: null,
                    regularExpression: $formField->regularExpression,
                    step: null,
                    customErrorMessageEnabled: $formField->customErrorMessageEnabled,
                    customErrorMessage: $formField->customErrorMessage,
                ),
            ),
        );
    }
}
