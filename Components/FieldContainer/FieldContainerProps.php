<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\FieldContainer;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class FieldContainerProps
{
    private function __construct(
        public ?string $id,
        public ?string $label,
        public ?string $inputId,
        public ?bool $isRequired,
    ) {
    }

    public static function create(
        ?string $id,
        ?string $label,
        ?string $inputId,
        ?bool $isRequired,
    ): self {
        return new self(
            id: $id,
            label: $label,
            inputId: $inputId,
            isRequired: $isRequired,
        );
    }
}
