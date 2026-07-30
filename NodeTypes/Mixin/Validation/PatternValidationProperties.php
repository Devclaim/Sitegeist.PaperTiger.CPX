<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation;

use PackageFactory\OPGM\Domain\Property\BackingTraitPropertyDefaultValueDeclaration;

/**
 * backing trait for {@see PatternValidationProvider}
 */
trait PatternValidationProperties
{
    public ?string $regularExpression;

    #[BackingTraitPropertyDefaultValueDeclaration(defaultValue: false)]
    public readonly bool $patternUseCustomMessage;

    public readonly ?string $patternMessage;
}
