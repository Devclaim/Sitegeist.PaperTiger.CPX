<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\AltchaField;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class AltchaFieldProps
{
    private function __construct(
        public ?string $name,
        public ?string $challengeUrl,
    ) {
    }

    public static function create(
        ?string $name,
        ?string $challengeUrl,
    ): self {
        return new self(
            name: $name,
            challengeUrl: $challengeUrl,
        );
    }
}
