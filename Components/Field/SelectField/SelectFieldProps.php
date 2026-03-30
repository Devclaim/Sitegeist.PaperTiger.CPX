<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\SelectField;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class SelectFieldProps
{
    private function __construct(
        public FieldContainerProps $fieldContainer,
        public string $name,
        public ?bool $isMultiple,
        public ?bool $isRequired,
        public ?bool $emptyOptionEnabled,
        public ?string $emptyLabel,
        public ?bool $customErrorMessageEnabled,
        public ?string $customErrorMessage,
    ) {
    }

    public static function create(
        FieldContainerProps $fieldContainer,
        string $name,
        ?bool $isMultiple,
        ?bool $isRequired,
        ?bool $emptyOptionEnabled,
        ?string $emptyLabel,
        ?bool $customErrorMessageEnabled,
        ?string $customErrorMessage,
    ): self {
        return new self(
            fieldContainer: $fieldContainer,
            name: $name,
            isMultiple: $isMultiple,
            isRequired: $isRequired,
            emptyOptionEnabled: $emptyOptionEnabled,
            emptyLabel: $emptyLabel,
            customErrorMessageEnabled: $customErrorMessageEnabled,
            customErrorMessage: $customErrorMessage,
        );
    }
}
