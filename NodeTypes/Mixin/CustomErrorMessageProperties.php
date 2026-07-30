<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field;

use PackageFactory\OPGM\Domain\Property\BackingTraitPropertyDefaultValueDeclaration;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProvider;

/**
 * backing trait for {@see CustomErrorMessageProvider}
 */
trait CustomErrorMessageProperties
{
    #[BackingTraitPropertyDefaultValueDeclaration(defaultValue: false)]
    public readonly bool $customErrorMessageEnabled;

    public readonly ?string $customErrorMessage;
}
