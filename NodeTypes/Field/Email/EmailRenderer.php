<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Email;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputField;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputFieldProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

final class EmailRenderer implements ContentNodeRendererInterface
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $name = $context->nodes->getStringValue($context->node, 'name') ?? $context->node->aggregateId->value;
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
                    type: 'email',
                    name: $name,
                    placeholder: $context->nodes->getStringValue($context->node, 'placeholder'),
                    isRequired: $context->nodes->getBoolValue($context->node, 'isRequired'),
                    minimumLength: null,
                    maximumLength: null,
                    regularExpression: null,
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
