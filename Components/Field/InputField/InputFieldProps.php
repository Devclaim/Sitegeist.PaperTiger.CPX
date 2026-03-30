<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\InputField;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class InputFieldProps
{
    private function __construct(
        public FieldContainerProps $fieldContainer,
        public string $type,
        public string $name,
        public ?string $placeholder,
        public ?bool $isRequired,
        public ?int $minimumLength,
        public ?int $maximumLength,
        public ?string $regularExpression,
        public ?string $minimum,
        public ?string $maximum,
        public ?string $step,
        public ?bool $customErrorMessageEnabled,
        public ?string $customErrorMessage,
    ) {
    }

    public static function create(
        FieldContainerProps $fieldContainer,
        string $type,
        string $name,
        ?string $placeholder,
        ?bool $isRequired,
        ?int $minimumLength,
        ?int $maximumLength,
        ?string $regularExpression,
        ?string $minimum,
        ?string $maximum,
        ?string $step,
        ?bool $customErrorMessageEnabled,
        ?string $customErrorMessage,
    ): self {
        return new self(
            fieldContainer: $fieldContainer,
            type: $type,
            name: $name,
            placeholder: $placeholder,
            isRequired: $isRequired,
            minimumLength: $minimumLength,
            maximumLength: $maximumLength,
            regularExpression: $regularExpression,
            minimum: $minimum,
            maximum: $maximum,
            step: $step,
            customErrorMessageEnabled: $customErrorMessageEnabled,
            customErrorMessage: $customErrorMessage,
        );
    }
}
