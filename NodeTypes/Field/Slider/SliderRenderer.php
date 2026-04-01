<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Slider;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputField;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputFieldProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

final class SliderRenderer implements ContentNodeRendererInterface
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $name = $context->nodes->getStringValue($context->node, 'name') ?? $context->node->aggregateId->value;
        $minimumValue = $context->nodes->getIntValue($context->node, 'minimumValue');
        $maximumValue = $context->nodes->getIntValue($context->node, 'maximumValue');
        $stepValue = $context->nodes->getIntValue($context->node, 'stepValue');
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
                    type: 'range',
                    name: $name,
                    placeholder: null,
                    isRequired: $context->nodes->getBoolValue($context->node, 'isRequired'),
                    minimumLength: null,
                    maximumLength: null,
                    regularExpression: null,
                    minimum: $minimumValue !== null ? (string)$minimumValue : null,
                    maximum: $maximumValue !== null ? (string)$maximumValue : null,
                    step: $stepValue !== null ? (string)$stepValue : null,
                    customErrorMessageEnabled: $context->nodes->getBoolValue($context->node, 'customErrorMessageEnabled'),
                    customErrorMessage: $context->nodes->getStringValue($context->node, 'customErrorMessage'),
                ),
            ),
        );
    }
}
