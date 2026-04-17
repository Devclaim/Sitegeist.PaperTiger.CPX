<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Dto;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class FormSubmissionValidationErrorCollection implements \Countable
{
    /**
     * @var FormSubmissionValidationError[]
     */
    public array $items;

    public function __construct(FormSubmissionValidationError ...$items)
    {
        $this->items = array_values($items);
    }

    public function count(): int
    {
        return count($this->items);
    }
}
