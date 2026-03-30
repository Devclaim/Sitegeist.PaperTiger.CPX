<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Dropdown;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\SelectField\SelectFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\AbstractFieldRenderer;

final class DropdownRenderer extends AbstractFieldRenderer
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return $this->wrapField($context, $this->createComponent(
            $this->components()->selectFieldComponent(),
            [
                'field' => SelectFieldProps::create(
                    fieldContainer: $this->createFieldContainerProps($context),
                    name: $this->nameOrIdentifier($context),
                    isMultiple: $this->boolProperty($context->node, 'isMultiple'),
                    isRequired: $this->boolProperty($context->node, 'isRequired'),
                    emptyOptionEnabled: $this->boolProperty($context->node, 'emptyOptionEnabled'),
                    emptyLabel: $this->stringProperty($context->node, 'emptyLabel'),
                    customErrorMessageEnabled: $this->boolProperty($context->node, 'customErrorMessageEnabled'),
                    customErrorMessage: $this->stringProperty($context->node, 'customErrorMessage'),
                ),
                'content' => $this->renderDropdownOptions(
                    options: $this->options($context->node),
                    includeEmptyOption: $this->boolProperty($context->node, 'emptyOptionEnabled') ?? false,
                    emptyLabel: $this->stringProperty($context->node, 'emptyLabel'),
                ),
            ],
        ));
    }
}
