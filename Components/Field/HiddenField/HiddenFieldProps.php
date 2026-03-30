<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\HiddenField;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class HiddenFieldProps
{
    private function __construct(
        public string $name,
        public ?string $value,
    ) {
    }

    public static function create(
        string $name,
        ?string $value,
    ): self {
        return new self(
            name: $name,
            value: $value,
        );
    }
}
