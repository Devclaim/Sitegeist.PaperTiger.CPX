<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\UploadField;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class UploadFieldProps
{
    private function __construct(
        public FieldContainerProps $fieldContainer,
        public string $name,
        public ?bool $isMultiple,
        public ?bool $isRequired,
        public ?string $allowedExtensions,
        public ?int $allowedFilesize,
        public ?bool $customErrorMessageEnabled,
        public ?string $customErrorMessage,
    ) {
    }

    public static function create(
        FieldContainerProps $fieldContainer,
        string $name,
        ?bool $isMultiple,
        ?bool $isRequired,
        ?string $allowedExtensions,
        ?int $allowedFilesize,
        ?bool $customErrorMessageEnabled,
        ?string $customErrorMessage,
    ): self {
        return new self(
            fieldContainer: $fieldContainer,
            name: $name,
            isMultiple: $isMultiple,
            isRequired: $isRequired,
            allowedExtensions: $allowedExtensions,
            allowedFilesize: $allowedFilesize,
            customErrorMessageEnabled: $customErrorMessageEnabled,
            customErrorMessage: $customErrorMessage,
        );
    }
}
