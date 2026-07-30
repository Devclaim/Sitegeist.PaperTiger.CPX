<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation;

use PackageFactory\OPGM\Domain\Property\BackingTraitPropertyDefaultValueDeclaration;

/**
 * backing trait for {@see StringLengthValidationProvider}
 */
trait StringLengthValidationProperties
{
    public readonly ?int $minimumLength;

    public readonly ?int $maximumLength;

    #[BackingTraitPropertyDefaultValueDeclaration(defaultValue: false)]
    public readonly bool $lengthUseCustomMessage;

    public readonly ?string $lengthMessage;
}
