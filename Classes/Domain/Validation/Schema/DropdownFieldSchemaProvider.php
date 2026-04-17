<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaInterface;

final class DropdownFieldSchemaProvider extends AbstractFieldSchemaProvider
{
    public function build(NeosContext $context, Node $fieldNode): ?SchemaInterface
    {
        $isMultiple = $context->nodes->getBoolValue($fieldNode, 'isMultiple') ?? false;

        $schema = $isMultiple
            ? $this->createArrayOfSchema($this->createSchema('string'))
            : $this->createSchema('string');

        $this->applyRequired($context, $fieldNode, $schema);

        return $schema;
    }
}
