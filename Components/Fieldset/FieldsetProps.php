<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Fieldset;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class FieldsetProps
{
    private function __construct(
        public ?string $id,
        public ?string $label,
    ) {
    }

    public static function create(
        ?string $id,
        ?string $label,
    ): self {
        return new self(
            id: $id,
            label: $label,
        );
    }
}
