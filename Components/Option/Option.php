<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Option;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class Option
{
    private function __construct(
        public string $label,
        public string $value,
    ) {
    }

    public static function create(
        string $label,
        string $value,
    ): self {
        return new self(
            label: $label,
            value: $value,
        );
    }
}
