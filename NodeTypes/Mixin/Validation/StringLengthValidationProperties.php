<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation;

/**
 * backing trait for {@see StringLengthValidationProvider}
 */
trait StringLengthValidationProperties
{
    public function __construct(
        public readonly ?int $minimumLength,
        public readonly ?int $maximumLength,
        public readonly ?string $lengthMessage,
        public readonly bool $lengthUseCustomMessage = false,
    ) {
    }
}
