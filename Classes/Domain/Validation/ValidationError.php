<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation;

use Neos\Error\Messages\Error;

/**
 * Carries the validationId so async clients can map server errors to a specific rule.
 */
final class ValidationError extends Error
{
    public function __construct(
        string $message,
        int $code,
        public readonly ?string $validationId,
    ) {
        parent::__construct($message, $code);
    }
}

