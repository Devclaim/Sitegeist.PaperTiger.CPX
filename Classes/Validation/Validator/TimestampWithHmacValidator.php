<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Validation\Validator;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Cryptography\HashService;
use Neos\Flow\Security\Exception\InvalidArgumentForHashGenerationException;
use Neos\Flow\Security\Exception\InvalidHashException;
use Neos\Flow\Validation\Validator\AbstractValidator;

/**
 * Validates a HMAC protected unix timestamp (created with HashService::appendHmac()).
 *
 * Used for the honeypot field to ensure the submission is neither "too fast" nor "too old".
 */
final class TimestampWithHmacValidator extends AbstractValidator
{
    protected $acceptsEmptyValues = false;

    /**
     * @var array<string, mixed>
     */
    protected $supportedOptions = [
        'minimumAge' => [10, 'Minimum age (seconds) for the hmacked timestamp', 'integer'],
        'maximumAge' => [86400, 'Maximum age (seconds) for the hmacked timestamp', 'integer'],
    ];

    #[Flow\Inject]
    protected HashService $hashService;

    protected function isValid($value): void
    {
        if (!is_string($value) || $value === '') {
            $this->addError('A timestamp with HMAC is expected.', 1744928381);
            return;
        }

        try {
            $timestamp = $this->hashService->validateAndStripHmac($value);
            $age = time() - (int)$timestamp;

            if ($age > (int)$this->options['maximumAge']) {
                $this->addError('Timestamp is too old.', 1744928382);
                return;
            }

            if ($age < (int)$this->options['minimumAge']) {
                $this->addError('Timestamp is too young.', 1744928383);
                return;
            }
        } catch (InvalidArgumentForHashGenerationException | InvalidHashException) {
            $this->addError('HMAC did not match.', 1744928384);
        }
    }
}

