<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation;

use PackageFactory\OPGM\Domain\Property\BackingTraitPropertyDefaultValueDeclaration;

/**
 * backing trait for {@see RequiredValidationProvider}
 */
trait RequiredValidationProperties
{
    #[BackingTraitPropertyDefaultValueDeclaration(defaultValue: false)]
    public readonly bool $isRequired;

    #[BackingTraitPropertyDefaultValueDeclaration(defaultValue: false)]
    public readonly bool $requiredUseCustomMessage;

    public readonly ?string $requiredMessage;
}
