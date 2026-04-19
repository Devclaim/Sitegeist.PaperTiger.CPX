<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaInterface;

final class TelephoneNumberFieldSchemaProvider extends AbstractFieldSchemaProvider
{
    public function supports(NeosContext $context, Node $fieldNode): bool
    {
        $nodeType = $context->nodes->tryGetNodeType($fieldNode);
        return $nodeType?->isOfType('Sitegeist.PaperTiger.CPX:Field.TelephoneNumber') ?? false;
    }

    public function build(NeosContext $context, Node $fieldNode): ?SchemaInterface
    {
        $schema = $this->createSchema('string');
        $this->applyRequiredValidation($context, $fieldNode, $schema);
        $this->applyPatternValidation($context, $fieldNode, $schema);

        return $schema;
    }
}
