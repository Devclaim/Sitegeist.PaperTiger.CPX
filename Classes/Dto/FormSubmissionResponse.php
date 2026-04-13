<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Dto;

use Neos\Flow\Annotations as Flow;
use Sitegeist\SchemeOnYou\Domain\Metadata\Response;
use Sitegeist\SchemeOnYou\Domain\Metadata\Schema;

#[Flow\Proxy(false)]
#[Schema(description: '', name: 'PaperTigerFormSubmissionResponse')]
#[Response(statusCode: 200, description: '')]
final readonly class FormSubmissionResponse
{
    public function __construct(
        public string $content,
    ) {
    }
}
