<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\TextareaField;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class TextareaFieldProps
{
    private function __construct(
        public FieldContainerProps $fieldContainer,
        public string $name,
        public ?string $value,
        public ?string $placeholder,
        public ?bool $isRequired,
        public ?int $lineNumber,
        public ?int $minimumLength,
        public ?int $maximumLength,
        public ?bool $customErrorMessageEnabled,
        public ?string $customErrorMessage,
    ) {
    }

    public static function create(
        FieldContainerProps $fieldContainer,
        string $name,
        ?string $value,
        ?string $placeholder,
        ?bool $isRequired,
        ?int $lineNumber,
        ?int $minimumLength,
        ?int $maximumLength,
        ?bool $customErrorMessageEnabled,
        ?string $customErrorMessage,
    ): self {
        return new self(
            fieldContainer: $fieldContainer,
            name: $name,
            value: $value,
            placeholder: $placeholder,
            isRequired: $isRequired,
            lineNumber: $lineNumber,
            minimumLength: $minimumLength,
            maximumLength: $maximumLength,
            customErrorMessageEnabled: $customErrorMessageEnabled,
            customErrorMessage: $customErrorMessage,
        );
    }
}
