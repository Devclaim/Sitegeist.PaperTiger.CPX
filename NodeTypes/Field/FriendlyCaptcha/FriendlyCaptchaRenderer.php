<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\FriendlyCaptcha;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\FriendlyCaptchaField\FriendlyCaptchaFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\AbstractFieldRenderer;

final class FriendlyCaptchaRenderer extends AbstractFieldRenderer
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return $this->createComponent(
            $this->components()->friendlyCaptchaFieldComponent(),
            [
                'field' => FriendlyCaptchaFieldProps::create(
                    name: 'friendlycaptcha_' . $context->node->aggregateId->value,
                ),
            ],
        );
    }
}
