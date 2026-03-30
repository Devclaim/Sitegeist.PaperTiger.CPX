<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Date;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\AbstractFieldRenderer;

final class DateRenderer extends AbstractFieldRenderer
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return $this->wrapField($context, $this->createComponent(
            $this->components()->inputFieldComponent(),
            [
                'field' => InputFieldProps::create(
                    fieldContainer: $this->createFieldContainerProps($context),
                    type: 'date',
                    name: $this->nameOrIdentifier($context),
                    placeholder: $this->stringProperty($context->node, 'placeholder'),
                    isRequired: $this->boolProperty($context->node, 'isRequired'),
                    minimumLength: null,
                    maximumLength: null,
                    regularExpression: null,
                    minimum: $this->formattedDateProperty($context->node, 'earliestDate'),
                    maximum: $this->formattedDateProperty($context->node, 'latestDate'),
                    step: null,
                    customErrorMessageEnabled: $this->boolProperty($context->node, 'customErrorMessageEnabled'),
                    customErrorMessage: $this->stringProperty($context->node, 'customErrorMessage'),
                ),
            ],
        ));
    }
}
