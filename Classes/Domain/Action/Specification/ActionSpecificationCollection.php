<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Action\Specification;

/**
 * Typed collection for future property-based action storage on the form node.
 *
 * @implements \IteratorAggregate<int, ActionSpecificationInterface>
 */
final readonly class ActionSpecificationCollection implements \Countable, \IteratorAggregate, \JsonSerializable
{
    /**
     * @param array<int, ActionSpecificationInterface> $items
     */
    public function __construct(
        public array $items = [],
    ) {
    }

    public static function empty(): self
    {
        return new self([]);
    }

    /**
     * @param array<int, ActionSpecificationInterface> $items
     */
    public static function fromItems(array $items): self
    {
        return new self(array_values($items));
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getIterator(): \Traversable
    {
        yield from $this->items;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function jsonSerialize(): array
    {
        return array_map(
            static fn (ActionSpecificationInterface $item): array => $item->jsonSerialize(),
            $this->items,
        );
    }
}
