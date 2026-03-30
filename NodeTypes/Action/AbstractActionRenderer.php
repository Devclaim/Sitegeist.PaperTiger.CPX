<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Action;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;

abstract class AbstractActionRenderer implements ContentNodeRendererInterface
{
    protected function stringProperty(Node $node, string $propertyName): ?string
    {
        $value = $node->getProperty($propertyName);

        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return $value !== '' ? $value : null;
        }

        if (is_scalar($value)) {
            return (string)$value;
        }

        return null;
    }

    protected function joinParts(?string ...$parts): ?string
    {
        $parts = array_values(array_filter($parts, static fn (?string $part) => $part !== null && $part !== ''));

        if ($parts === []) {
            return null;
        }

        return implode(' ', $parts);
    }
}
