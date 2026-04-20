<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Dto;

use Neos\Flow\Annotations as Flow;
use Sitegeist\SchemeOnYou\Domain\Metadata\Response;

#[Flow\Proxy(false)]
#[Response(statusCode: 200, description: '')]
final readonly class FormSubmissionJsonResponse
{
    public function __construct(
        public bool $success,
        public FormSubmissionValidationErrorCollection $errors,
        public ?string $redirectUri = null,
        public ?string $content = null,
        public ?string $message = null,
    ) {
    }
}

