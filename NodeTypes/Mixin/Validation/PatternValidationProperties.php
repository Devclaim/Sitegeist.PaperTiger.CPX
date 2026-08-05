<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation;

/**
 * backing trait for {@see PatternValidationProvider}
 */
trait PatternValidationProperties
{
    public function __construct(
        public readonly ?string $regularExpression,
        public readonly ?string $patternMessage,
        public readonly bool $patternUseCustomMessage = false,
    ) {
    }
}
