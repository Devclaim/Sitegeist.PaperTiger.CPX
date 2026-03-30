<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\ButtonField;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class ButtonFieldProps
{
    private function __construct(
        public ?string $label,
    ) {
    }

    public static function create(
        ?string $label,
    ): self {
        return new self(
            label: $label,
        );
    }
}
