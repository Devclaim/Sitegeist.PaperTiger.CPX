<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Number;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\AbstractFieldRenderer;

final class NumberRenderer extends AbstractFieldRenderer
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return $this->wrapField($context, $this->createComponent(
            $this->components()->inputFieldComponent(),
            [
                'field' => InputFieldProps::create(
                    fieldContainer: $this->createFieldContainerProps($context),
                    type: 'number',
                    name: $this->nameOrIdentifier($context),
                    placeholder: $this->stringProperty($context->node, 'placeholder'),
                    isRequired: $this->boolProperty($context->node, 'isRequired'),
                    minimumLength: null,
                    maximumLength: null,
                    regularExpression: null,
                    minimum: ($minimum = $this->intProperty($context->node, 'minimumValue')) !== null ? (string)$minimum : null,
                    maximum: ($maximum = $this->intProperty($context->node, 'maximumValue')) !== null ? (string)$maximum : null,
                    step: null,
                    customErrorMessageEnabled: $this->boolProperty($context->node, 'customErrorMessageEnabled'),
                    customErrorMessage: $this->stringProperty($context->node, 'customErrorMessage'),
                ),
            ],
        ));
    }
}
