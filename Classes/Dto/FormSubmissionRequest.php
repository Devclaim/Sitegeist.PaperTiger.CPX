<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Dto;

use Neos\Flow\Annotations as Flow;
use Sitegeist\SchemeOnYou\Domain\Metadata\Schema;

#[Flow\Proxy(false)]
#[Schema(description: '', name: 'PaperTigerFormSubmissionRequest')]
final readonly class FormSubmissionRequest
{
    public function __construct(
        public string $nodeAggregateId,
    ) {
    }
}
