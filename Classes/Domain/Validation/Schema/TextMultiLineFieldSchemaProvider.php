<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaInterface;
use Neos\Flow\Annotations as Flow;

final class TextMultiLineFieldSchemaProvider extends AbstractFieldSchemaProvider
{
    public function supports(NeosContext $context, Node $fieldNode): bool
    {
        $nodeType = $context->nodes->tryGetNodeType($fieldNode);
        return $nodeType?->isOfType('Sitegeist.PaperTiger.CPX:Field.Text.MultiLine') ?? false;
    }

    public function build(NeosContext $context, Node $fieldNode): ?SchemaInterface
    {
        $schema = $this->createSchema('string');
        $this->applyRequiredValidation($context, $fieldNode, $schema);
        $this->applyStringLengthValidation($context, $fieldNode, $schema);

        return $schema;
    }
}
