<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Button;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\ButtonField\ButtonFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\AbstractFieldRenderer;

final class ButtonRenderer extends AbstractFieldRenderer
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return $this->createComponent(
            $this->components()->buttonFieldComponent(),
            [
                'field' => ButtonFieldProps::create(
                    label: $this->stringProperty($context->node, 'label'),
                ),
            ],
        );
    }
}
