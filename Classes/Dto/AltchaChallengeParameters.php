<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Dto;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class AltchaChallengeParameters
{
    public function __construct(
        public string $algorithm,
        public string $nonce,
        public string $salt,
        public int $cost,
        public int $keyLength,
        public string $keyPrefix,
        public ?string $keySignature = null,
        public ?int $memoryCost = null,
        public ?int $parallelism = null,
        public ?int $expiresAt = null,
    ) {
    }
}
