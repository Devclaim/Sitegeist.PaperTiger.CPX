<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Text\MultiLine;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\TextareaField\TextareaFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\AbstractFieldRenderer;

final class MultiLineRenderer extends AbstractFieldRenderer
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return $this->wrapField($context, $this->createComponent(
            $this->components()->textareaFieldComponent(),
            [
                'field' => TextareaFieldProps::create(
                    fieldContainer: $this->createFieldContainerProps($context),
                    name: $this->nameOrIdentifier($context),
                    value: null,
                    placeholder: $this->stringProperty($context->node, 'placeholder'),
                    isRequired: $this->boolProperty($context->node, 'isRequired'),
                    lineNumber: $this->intProperty($context->node, 'lineNumber'),
                    minimumLength: $this->intProperty($context->node, 'minimumLength'),
                    maximumLength: $this->intProperty($context->node, 'maximumLength'),
                    regularExpression: $this->stringProperty($context->node, 'regularExpression'),
                    customErrorMessageEnabled: $this->boolProperty($context->node, 'customErrorMessageEnabled'),
                    customErrorMessage: $this->stringProperty($context->node, 'customErrorMessage'),
                ),
            ],
        ));
    }
}
