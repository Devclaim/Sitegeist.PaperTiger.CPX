<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Dto;

use Neos\Flow\Annotations as Flow;
use Sitegeist\SchemeOnYou\Domain\Metadata\Schema;

#[Flow\Proxy(false)]
#[Schema(description: '', name: 'PaperTigerFormSubmissionArgumentCollection')]
final readonly class FormSubmissionArgumentCollection
{
    /**
     * @var FormSubmissionArgument[]
     */
    public array $items;

    public function __construct(FormSubmissionArgument ...$items)
    {
        $this->items = array_values($items);
    }
}
