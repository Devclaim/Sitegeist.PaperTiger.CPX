<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\FriendlyCaptcha;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\FriendlyCaptchaField\FriendlyCaptchaField;
use Sitegeist\PaperTiger\CPX\Components\Field\FriendlyCaptchaField\FriendlyCaptchaFieldProps;

final class FriendlyCaptchaRenderer implements ContentNodeRendererInterface
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return FriendlyCaptchaField::create(
            field: FriendlyCaptchaFieldProps::create(
                name: 'friendlycaptcha_' . $context->node->aggregateId->value,
            ),
        );
    }
}
