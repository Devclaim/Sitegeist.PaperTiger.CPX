<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Field;

/**
 * backing trait for {@see DropdownProvider}
 */
trait DropdownProperties
{
    public function __construct(
        public readonly ?string $emptyLabel,
        public readonly bool $emptyOptionEnabled = true,
    ) {
    }
}
