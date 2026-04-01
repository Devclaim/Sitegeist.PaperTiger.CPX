<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Text\SingleLine;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputField;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputFieldProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

final class SingleLineRenderer implements ContentNodeRendererInterface
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $name = $context->nodes->getStringValue($context->node, 'name') ?? $context->node->aggregateId->value;
        $minimumLength = $context->nodes->getIntValue($context->node, 'minimumLength');
        $maximumLength = $context->nodes->getIntValue($context->node, 'maximumLength');
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
                    type: 'text',
                    name: $name,
                    placeholder: $context->nodes->getStringValue($context->node, 'placeholder'),
                    isRequired: $context->nodes->getBoolValue($context->node, 'isRequired'),
                    minimumLength: $minimumLength,
                    maximumLength: $maximumLength,
                    regularExpression: $context->nodes->getStringValue($context->node, 'regularExpression'),
                    minimum: null,
                    maximum: null,
                    step: null,
                    customErrorMessageEnabled: $context->nodes->getBoolValue($context->node, 'customErrorMessageEnabled'),
                    customErrorMessage: $context->nodes->getStringValue($context->node, 'customErrorMessage'),
                ),
            ),
        );
    }
}
