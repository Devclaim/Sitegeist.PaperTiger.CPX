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
        public ?string $auto,
        public ?string $display,
        public ?string $type,
        public ?string $configuration,
    ) {
    }

    public static function create(
        ?string $name,
        ?string $challengeUrl,
        ?string $auto,
        ?string $display,
        ?string $type,
        ?string $configuration,
    ): self {
        return new self(
            name: $name,
            challengeUrl: $challengeUrl,
            auto: $auto,
            display: $display,
            type: $type,
            configuration: $configuration,
        );
    }
}
