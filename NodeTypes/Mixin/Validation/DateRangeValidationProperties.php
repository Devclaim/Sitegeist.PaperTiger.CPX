<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation;

/**
 * backing trait for {@see DateRangeValidationProvider}
 */
trait DateRangeValidationProperties
{
    public function __construct(
        public readonly ?\DateTimeImmutable $earliestDate,
        public readonly ?\DateTimeImmutable $latestDate,
        public readonly ?string $dateRangeMessage,
        public readonly bool $dateRangeUseCustomMessage = false,
    ) {
    }
}
