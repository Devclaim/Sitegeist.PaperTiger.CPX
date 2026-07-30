<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation;

use PackageFactory\OPGM\Domain\Property\BackingTraitPropertyDefaultValueDeclaration;

/**
 * backing trait for {@see DateRangeValidationProvider}
 */
trait DateRangeValidationProperties
{
    public readonly ?\DateTimeImmutable $earliestDate;

    public readonly ?\DateTimeImmutable $latestDate;

    #[BackingTraitPropertyDefaultValueDeclaration(defaultValue: false)]
    public readonly bool $dateRangeUseCustomMessage;

    public readonly ?string $dateRangeMessage;
}
