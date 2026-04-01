<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Hidden;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenField;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenFieldProps;

final class HiddenRenderer implements ContentNodeRendererInterface
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $name = $context->nodes->getStringValue($context->node, 'name') ?? $context->node->aggregateId->value;

        return HiddenField::create(
            field: HiddenFieldProps::create(
                name: $name,
                value: $context->nodes->getStringValue($context->node, 'value'),
            ),
        );
    }
}
