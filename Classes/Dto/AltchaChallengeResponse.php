<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Dto;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class AltchaChallengeResponse
{
    public function __construct(
        public AltchaChallengeParameters $parameters,
        public ?string $signature = null,
    ) {
    }
}
