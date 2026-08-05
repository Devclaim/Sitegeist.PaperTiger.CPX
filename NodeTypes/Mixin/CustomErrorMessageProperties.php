<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field;

use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProvider;

/**
 * backing trait for {@see CustomErrorMessageProvider}
 */
trait CustomErrorMessageProperties
{
    public function __construct(
        public readonly ?string $customErrorMessage,
        public readonly bool $customErrorMessageEnabled = false,
    ) {
    }
}
