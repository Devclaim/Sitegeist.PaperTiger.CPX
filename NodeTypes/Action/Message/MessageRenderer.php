<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Action\Message;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\ActionPreviewCard\ActionPreviewCard;
use Sitegeist\PaperTiger\CPX\Components\MessageActionPreview\MessageActionPreview;

final class MessageRenderer implements ContentNodeRendererInterface
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return ActionPreviewCard::create(
            label: 'Message',
            content: MessageActionPreview::create(
                message: $context->nodes->getStringValue($context->node, 'message') ?? '',
            ),
        );
    }
}
