<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Field;

use PackageFactory\OPGM\Domain\Property\BackingTraitPropertyDefaultValueDeclaration;

/**
 * backing trait for {@see DropdownProvider}
 */
trait DropdownProperties
{
    #[BackingTraitPropertyDefaultValueDeclaration(defaultValue: true)]
    public readonly bool $emptyOptionEnabled;

    public readonly ?string $emptyLabel;
}
