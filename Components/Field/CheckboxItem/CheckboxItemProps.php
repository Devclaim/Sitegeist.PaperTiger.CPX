<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\CheckboxItem;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class CheckboxItemProps
{
    private function __construct(
        public string $name,
        public string $value,
        public string $label,
        public ?bool $isRequired,
    ) {
    }

    public static function create(
        string $name,
        string $value,
        string $label,
        ?bool $isRequired,
    ): self {
        return new self(
            name: $name,
            value: $value,
            label: $label,
            isRequired: $isRequired,
        );
    }
}
