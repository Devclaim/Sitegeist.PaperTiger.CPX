<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Dto;

use Neos\Flow\Annotations as Flow;
use Sitegeist\SchemeOnYou\Domain\Metadata\Schema;

#[Flow\Proxy(false)]
#[Schema(description: '', name: 'PaperTigerFormSubmissionArgument')]
final readonly class FormSubmissionArgument
{
    public function __construct(
        public string $name,
        public string $value,
    ) {
    }
}
