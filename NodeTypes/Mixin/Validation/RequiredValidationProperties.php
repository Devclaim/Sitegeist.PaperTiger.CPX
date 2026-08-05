<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation;

/**
 * backing trait for {@see RequiredValidationProvider}
 */
trait RequiredValidationProperties
{
    public function __construct(
        public readonly ?string $requiredMessage,
        public readonly bool $isRequired = false,
        public readonly bool $requiredUseCustomMessage = false,
    ) {
    }
}
