<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaInterface;

final class HoneypotFieldSchemaProvider extends AbstractFieldSchemaProvider
{
    public function supports(NeosContext $context, Node $fieldNode): bool
    {
        $nodeType = $context->nodes->tryGetNodeType($fieldNode);
        return $nodeType?->isOfType('Sitegeist.PaperTiger.CPX:Field.Honeypot') ?? false;
    }

    public function build(NeosContext $context, Node $fieldNode): ?SchemaInterface
    {
        return $this->createSchemaCollection([
            'one' => $this->createSchema('string')
                ->validator('StringLength', ['minimum' => 0, 'maximum' => 0]),
            'two' => $this->createSchema('string')
                ->isRequired()
                ->validator('Sitegeist.PaperTiger.CPX:TimestampWithHmac', [
                    'minimumAge' => 10,
                    'maximumAge' => 86400,
                ]),
            'three' => $this->createSchema('string')
                ->isRequired()
                ->validator('Sitegeist.PaperTiger.CPX:TimestampWithHmac', [
                    'minimumAge' => 10,
                    'maximumAge' => 86400,
                ]),
        ]);
    }
}