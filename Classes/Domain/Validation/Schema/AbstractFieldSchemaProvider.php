<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\Flow\Property\PropertyMapper;
use Neos\Flow\Property\PropertyMappingConfiguration;
use Neos\Flow\Validation\ValidatorResolver;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\ArrayOfSchemaDefinition;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaCollectionDefinition;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaDefinition;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaInterface;
use Neos\Flow\Annotations as Flow;

abstract class AbstractFieldSchemaProvider implements FieldSchemaProviderInterface
{
    public function __construct(
        protected readonly PropertyMapper $propertyMapper,
        protected readonly PropertyMappingConfiguration $propertyMappingConfiguration,
        protected readonly ValidatorResolver $validatorResolver,
    ) {
    }

    protected function createSchema(string $targetType = 'string'): SchemaDefinition
    {
        return new SchemaDefinition(
            $this->propertyMapper,
            $this->propertyMappingConfiguration,
            $this->validatorResolver,
            $targetType,
        );
    }

    protected function createArrayOfSchema(?SchemaInterface $itemSchema = null): ArrayOfSchemaDefinition
    {
        return new ArrayOfSchemaDefinition(
            $this->propertyMapper,
            $this->propertyMappingConfiguration,
            $this->validatorResolver,
            $itemSchema,
        );
    }

    /**
     * @param array<string, SchemaInterface> $schemas
     */
    protected function createSchemaCollection(array $schemas): SchemaCollectionDefinition
    {
        return new SchemaCollectionDefinition($schemas);
    }

    protected function applyRequired(NeosContext $context, Node $fieldNode, SchemaDefinition|ArrayOfSchemaDefinition $schema): void
    {
        if (($context->nodes->getBoolValue($fieldNode, 'isRequired') ?? false) === true) {
            $schema->isRequired();
        }
    }

    protected function normalizeRegularExpression(string $pattern): string
    {
        return '/^' . $pattern . '$/';
    }
}
