<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field;

use Neos\Flow\Annotations as Flow;

/**
 * @implements \IteratorAggregate<FormField|FieldCollection>
 */
#[Flow\Proxy(false)]
final readonly class FormFields implements \IteratorAggregate
{
    /**
     * @var array<FormField|FieldCollection>
     */
    public array $items;

    public function __construct(FormField|FieldCollection ...$items)
    {
        $this->items = array_values($items);
    }

    public function getIterator(): \Traversable
    {
        yield from $this->items;
    }
}
