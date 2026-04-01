<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Action\Redirect;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\ActionPreviewCard\ActionPreviewCard;
use Sitegeist\PaperTiger\CPX\Components\RedirectActionPreview\RedirectActionPreview;
use Sitegeist\PaperTiger\CPX\Components\RedirectActionPreviewError\RedirectActionPreviewError;

final class RedirectRenderer implements ContentNodeRendererInterface
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $uri = $context->nodes->getStringValue($context->node, 'uri');

        return ActionPreviewCard::create(
            label: 'Redirect',
            content: $uri === null
                ? RedirectActionPreviewError::create(message: 'No Uri set')
                : RedirectActionPreview::create(uri: $uri),
        );
    }
}
