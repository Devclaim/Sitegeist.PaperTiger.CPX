<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Dto;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class FormSubmissionValidationError
{
    public function __construct(
        public string $fieldName,
        public string $message,
    ) {
    }
}
