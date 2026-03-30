<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\CheckBoxes;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\CheckboxGroupField\CheckboxGroupFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\AbstractFieldRenderer;

final class CheckBoxesRenderer extends AbstractFieldRenderer
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $name = $this->nameOrIdentifier($context);
        $isRequired = $this->boolProperty($context->node, 'isRequired');

        return $this->wrapField($context, $this->createComponent(
            $this->components()->checkboxGroupFieldComponent(),
            [
                'field' => CheckboxGroupFieldProps::create(
                    fieldContainer: $this->createFieldContainerProps($context),
                    name: $name,
                    isRequired: $isRequired,
                    customErrorMessageEnabled: $this->boolProperty($context->node, 'customErrorMessageEnabled'),
                    customErrorMessage: $this->stringProperty($context->node, 'customErrorMessage'),
                ),
                'content' => $this->renderCheckboxOptions(
                    options: $this->options($context->node),
                    name: $name,
                    isRequired: $isRequired,
                ),
            ],
        ));
    }
}
