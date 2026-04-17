<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaInterface;

final class TextSingleLineFieldSchemaProvider extends AbstractFieldSchemaProvider
{
    public function supports(NeosContext $context, Node $fieldNode): bool
    {
        $nodeType = $context->nodes->tryGetNodeType($fieldNode);
        return $nodeType?->isOfType('Sitegeist.PaperTiger.CPX:Field.Text.SingleLine') ?? false;
    }

    public function build(NeosContext $context, Node $fieldNode): ?SchemaInterface
    {
        $schema = $this->createSchema('string');
        $this->applyRequired($context, $fieldNode, $schema);

        $stringLengthOptions = array_filter([
            'minimum' => $context->nodes->getIntValue($fieldNode, 'minimumLength'),
            'maximum' => $context->nodes->getIntValue($fieldNode, 'maximumLength'),
        ], static fn (mixed $value): bool => $value !== null);

        if ($stringLengthOptions !== []) {
            $schema->validator('StringLength', $stringLengthOptions);
        }

        $regularExpression = $context->nodes->getStringValue($fieldNode, 'regularExpression');
        if ($regularExpression !== null && $regularExpression !== '') {
            $schema->validator('RegularExpression', [
                'regularExpression' => $this->normalizeRegularExpression($regularExpression),
            ]);
        }

        return $schema;
    }
}