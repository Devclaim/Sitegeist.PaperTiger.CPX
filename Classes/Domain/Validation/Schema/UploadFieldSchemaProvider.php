<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaInterface;
use Sitegeist\PaperTiger\CPX\Domain\Validation\Validator\UploadedFileCollectionValidator;
use Sitegeist\PaperTiger\CPX\Domain\Validation\Validator\UploadedFileValidator;

final class UploadFieldSchemaProvider extends AbstractFieldSchemaProvider
{
    public function supports(NeosContext $context, Node $fieldNode): bool
    {
        $nodeType = $context->nodes->tryGetNodeType($fieldNode);
        return $nodeType?->isOfType('Sitegeist.PaperTiger.CPX:Field.Upload') ?? false;
    }

    public function build(NeosContext $context, Node $fieldNode): ?SchemaInterface
    {
        $isMultiple = $context->nodes->getBoolValue($fieldNode, 'isMultiple') ?? false;
        $schema = $isMultiple ? $this->createSchema('array') : $this->createSchema('Psr\Http\Message\UploadedFileInterface');
        $this->applyRequired($context, $fieldNode, $schema);

        $options = array_filter([
            'allowedExtensions' => $fieldNode->getProperty('allowedExtensions'),
            'maximumSize' => $context->nodes->getIntValue($fieldNode, 'allowedFilesize'),
        ], static fn (mixed $value): bool => $value !== null && $value !== []);

        if ($options !== []) {
            $schema->validator(
                $isMultiple ? UploadedFileCollectionValidator::class : UploadedFileValidator::class,
                $options
            );
        }

        return $schema;
    }
}