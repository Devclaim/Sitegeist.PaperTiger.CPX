<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Dto;

use Neos\Flow\Annotations as Flow;
use Sitegeist\SchemeOnYou\Domain\Metadata\Response;

#[Flow\Proxy(false)]
#[Response(statusCode: 400, description: '')]
final readonly class FormSubmissionJsonErrorResponse
{
    public function __construct(
        public bool $success,
        public FormSubmissionValidationErrorCollection $errors,
    ) {
    }
}
