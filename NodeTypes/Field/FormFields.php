<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field;

use Neos\ContentRepository\Core\Projection\ContentGraph\ContentSubgraphInterface;
use Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindChildNodesFilter;
use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\Flow\Annotations as Flow;
use PackageFactory\OPGM\Domain\ObjectPropertyGraphMapper;

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

    public static function fromNodeChildren(Node $parentNode, ContentSubgraphInterface $subgraph): self
    {
        $items = [];

        foreach ($subgraph->findChildNodes(
            $parentNode->aggregateId,
            FindChildNodesFilter::create(),
        ) as $childNode) {
            $item = ObjectPropertyGraphMapper::map($childNode, $subgraph);
            if ($item instanceof FormField || $item instanceof FieldCollection) {
                $items[] = $item;
            }
        }

        return new self(...$items);
    }

    public function getIterator(): \Traversable
    {
        yield from $this->items;
    }
}
