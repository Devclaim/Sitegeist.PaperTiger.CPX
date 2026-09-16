<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field;

use Neos\Flow\Annotations as Flow;

/**
 * @implements \IteratorAggregate<FieldCollection>
 */
#[Flow\Proxy(false)]
final readonly class FieldCollections implements \IteratorAggregate
{
    /**
     * @var array<FieldCollection>
     */
    public array $items;

    public function __construct(FieldCollection ...$items)
    {
        $this->items = array_values($items);
    }

    public function getIterator(): \Traversable
    {
        yield from $this->items;
    }
}
