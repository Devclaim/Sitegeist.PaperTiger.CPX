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
        public ?bool $isChecked,
        public ?bool $isRequired,
        public ?bool $customErrorMessageEnabled,
        public ?string $customErrorMessage,
    ) {
    }

    public static function create(
        string $name,
        string $value,
        string $label,
        ?bool $isChecked,
        ?bool $isRequired,
        ?bool $customErrorMessageEnabled,
        ?string $customErrorMessage,
    ): self {
        return new self(
            name: $name,
            value: $value,
            label: $label,
            isChecked: $isChecked,
            isRequired: $isRequired,
            customErrorMessageEnabled: $customErrorMessageEnabled,
            customErrorMessage: $customErrorMessage,
        );
    }
}
