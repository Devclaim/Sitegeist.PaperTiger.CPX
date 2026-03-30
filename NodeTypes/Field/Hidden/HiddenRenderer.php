<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Hidden;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\AbstractFieldRenderer;

final class HiddenRenderer extends AbstractFieldRenderer
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return $this->createComponent(
            $this->components()->hiddenFieldComponent(),
            [
                'field' => HiddenFieldProps::create(
                    name: $this->nameOrIdentifier($context),
                    value: $this->stringProperty($context->node, 'value'),
                ),
            ],
        );
    }
}
