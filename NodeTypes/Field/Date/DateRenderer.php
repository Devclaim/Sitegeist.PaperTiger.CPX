<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Date;

use DateTimeInterface;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputField;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputFieldProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

final class DateRenderer implements ContentNodeRendererInterface
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $name = $context->nodes->getStringValue($context->node, 'name') ?? $context->node->aggregateId->value;
        $earliestDate = $context->nodes->getObjectValue($context->node, 'earliestDate', DateTimeInterface::class);
        $latestDate = $context->nodes->getObjectValue($context->node, 'latestDate', DateTimeInterface::class);
        $fieldContainer = FieldContainerProps::create(
            id: 'fieldcontainer_' . $name,
            label: $context->nodes->getStringValue($context->node, 'label'),
            inputId: 'field_' . $name,
            isRequired: $context->nodes->getBoolValue($context->node, 'isRequired'),
        );

        return FieldContainerFactory::create(
            $context,
            InputField::create(
                field: InputFieldProps::create(
                    fieldContainer: $fieldContainer,
                    type: 'date',
                    name: $name,
                    placeholder: $context->nodes->getStringValue($context->node, 'placeholder'),
                    isRequired: $context->nodes->getBoolValue($context->node, 'isRequired'),
                    minimumLength: null,
                    maximumLength: null,
                    regularExpression: null,
                    minimum: $earliestDate?->format('Y-m-d'),
                    maximum: $latestDate?->format('Y-m-d'),
                    step: null,
                    customErrorMessageEnabled: $context->nodes->getBoolValue($context->node, 'customErrorMessageEnabled'),
                    customErrorMessage: $context->nodes->getStringValue($context->node, 'customErrorMessage'),
                ),
            ),
        );
    }
}
