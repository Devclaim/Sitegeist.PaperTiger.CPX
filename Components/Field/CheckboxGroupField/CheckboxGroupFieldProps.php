<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\CheckboxGroupField;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class CheckboxGroupFieldProps
{
    private function __construct(
        public FieldContainerProps $fieldContainer,
        public string $name,
        public ?bool $isRequired,
        public ?bool $customErrorMessageEnabled,
        public ?string $customErrorMessage,
    ) {
    }

    public static function create(
        FieldContainerProps $fieldContainer,
        string $name,
        ?bool $isRequired,
        ?bool $customErrorMessageEnabled,
        ?string $customErrorMessage,
    ): self {
        return new self(
            fieldContainer: $fieldContainer,
            name: $name,
            isRequired: $isRequired,
            customErrorMessageEnabled: $customErrorMessageEnabled,
            customErrorMessage: $customErrorMessage,
        );
    }
}
