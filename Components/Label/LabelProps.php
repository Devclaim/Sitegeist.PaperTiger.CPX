<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Label;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class LabelProps
{
    private function __construct(
        public ?string $inputId,
        public ?string $label,
        public ?bool $isRequired,
    ) {
    }

    public static function create(
        ?string $inputId,
        ?string $label,
        ?bool $isRequired,
    ): self {
        return new self(
            inputId: $inputId,
            label: $label,
            isRequired: $isRequired,
        );
    }
}
