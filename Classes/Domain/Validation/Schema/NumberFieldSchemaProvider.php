<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaInterface;

final class NumberFieldSchemaProvider extends AbstractFieldSchemaProvider
{
    public function supports(NeosContext $context, Node $fieldNode): bool
    {
        $nodeType = $context->nodes->tryGetNodeType($fieldNode);
        return $nodeType?->isOfType('Sitegeist.PaperTiger.CPX:Field.Number') ?? false;
    }

    public function build(NeosContext $context, Node $fieldNode): ?SchemaInterface
    {
        $schema = $this->createSchema('integer');
        $this->applyRequiredValidation($context, $fieldNode, $schema);

        $options = array_filter([
            'minimum' => $context->nodes->getIntValue($fieldNode, 'minimumValue'),
            'maximum' => $context->nodes->getIntValue($fieldNode, 'maximumValue'),
        ], static fn (mixed $value): bool => $value !== null);

        if ($options !== []) {
            $schema->validatorWithId('numberRange', 'NumberRange', $options);
        }

        return $schema;
    }
}
