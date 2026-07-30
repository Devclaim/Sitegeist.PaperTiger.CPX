<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Date;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\OPGM\Domain\ObjectPropertyGraphMapper;
use Sitegeist\PaperTiger\CPX\Domain\PaperTigerFormState;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputFieldProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

final class DateRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly FieldContainerFactory $fieldContainerFactory,
        private readonly FieldComponentFactory $fieldComponentFactory,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $date = ObjectPropertyGraphMapper::map($context->node, $context->subgraph, Date::class);
        $formState = PaperTigerFormState::fromRequest($context->request);
        $fieldContainer = FieldContainerProps::create(
            id: 'fieldcontainer_' . $date->name,
            label: $date->label,
            inputId: 'field_' . $date->name,
            isRequired: $date->isRequired,
            hasErrors: $formState?->hasErrorsFor($date->name),
        );

        return $this->fieldContainerFactory->create(
            $context,
            $this->fieldComponentFactory->createDate(
                field: InputFieldProps::create(
                    fieldContainer: $fieldContainer,
                    type: 'date',
                    name: $date->name,
                    value: $formState?->getStringValue($date->name),
                    placeholder: $date->placeholder,
                    isRequired: $date->isRequired,
                    minimumLength: $date->earliestDate?->format('Y-m-d'),
                    maximumLength: $date->latestDate?->format('Y-m-d'),
                    regularExpression: null,
                    step: null,
                    customErrorMessageEnabled: $date->dateRangeUseCustomMessage,
                    customErrorMessage: $date->dateRangeMessage,
                ),
            ),
        );
    }
}
