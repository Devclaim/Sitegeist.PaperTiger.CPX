<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Text\SingleLine;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\AbstractFieldRenderer;

final class SingleLineRenderer extends AbstractFieldRenderer
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return $this->wrapField($context, $this->createComponent(
            $this->components()->inputFieldComponent(),
            [
                'field' => InputFieldProps::create(
                    fieldContainer: $this->createFieldContainerProps($context),
                    type: 'text',
                    name: $this->nameOrIdentifier($context),
                    placeholder: $this->stringProperty($context->node, 'placeholder'),
                    isRequired: $this->boolProperty($context->node, 'isRequired'),
                    minimumLength: $this->intProperty($context->node, 'minimumLength'),
                    maximumLength: $this->intProperty($context->node, 'maximumLength'),
                    regularExpression: $this->stringProperty($context->node, 'regularExpression'),
                    minimum: null,
                    maximum: null,
                    step: null,
                    customErrorMessageEnabled: $this->boolProperty($context->node, 'customErrorMessageEnabled'),
                    customErrorMessage: $this->stringProperty($context->node, 'customErrorMessage'),
                ),
            ],
        ));
    }
}
