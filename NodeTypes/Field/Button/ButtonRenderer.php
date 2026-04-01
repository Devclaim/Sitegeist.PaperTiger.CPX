<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Button;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\ButtonField\ButtonField;
use Sitegeist\PaperTiger\CPX\Components\Field\ButtonField\ButtonFieldProps;

final class ButtonRenderer implements ContentNodeRendererInterface
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return ButtonField::create(
            field: ButtonFieldProps::create(
                label: $context->nodes->getStringValue($context->node, 'label'),
            ),
        );
    }
}
