<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain;

use Sitegeist\PaperTiger\CPX\Dto\FormSubmissionValidationError;
use Sitegeist\PaperTiger\CPX\Dto\FormSubmissionValidationErrorCollection;

final readonly class FormSubmissionValidationResult
{
    /**
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        public array $arguments,
        public FormSubmissionValidationErrorCollection $errors,
    ) {
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function toErrorString(): string
    {
        if (!$this->hasErrors()) {
            return 'OK';
        }

        return implode(
            PHP_EOL,
            array_map(
                static fn (FormSubmissionValidationError $error): string => $error->fieldName . ': ' . $error->message,
                $this->errors->items,
            ),
        );
    }
}
