<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\Flow\Property\PropertyMapper;
use Neos\Flow\Property\PropertyMappingConfiguration;
use Neos\Flow\Validation\ValidatorResolver;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\ArrayOfSchemaDefinition;
use Sitegeist\PaperTiger\CPX\Domain\Validation\FlowValidationErrorCodes;
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

    protected function applyRequiredValidation(NeosContext $context, Node $fieldNode, SchemaDefinition|ArrayOfSchemaDefinition $schema): void
    {
        $this->applyRequired($context, $fieldNode, $schema);

        $useCustom = $context->nodes->getBoolValue($fieldNode, 'requiredUseCustomMessage')
            ?? $context->nodes->getBoolValue($fieldNode, 'useCustomRequiredMessage')
            ?? false;
        if (!$useCustom) {
            return;
        }

        $requiredMessage = $context->nodes->getStringValue($fieldNode, 'requiredMessage');
        if (is_string($requiredMessage) && $requiredMessage !== '') {
            $schema->overrideErrorMessages(FlowValidationErrorCodes::REQUIRED_CODES, $requiredMessage);
        }
    }

    protected function applyStringLengthValidation(NeosContext $context, Node $fieldNode, SchemaDefinition $schema): void
    {
        $stringLengthOptions = array_filter([
            'minimum' => $context->nodes->getIntValue($fieldNode, 'minimumLength'),
            'maximum' => $context->nodes->getIntValue($fieldNode, 'maximumLength'),
        ], static fn (mixed $value): bool => $value !== null);

        if ($stringLengthOptions !== []) {
            $schema->validator('StringLength', $stringLengthOptions);
        }

        $useCustom = $context->nodes->getBoolValue($fieldNode, 'lengthUseCustomMessage')
            ?? $context->nodes->getBoolValue($fieldNode, 'useCustomStringLengthMessage')
            ?? false;
        if (!$useCustom) {
            return;
        }

        $message = $context->nodes->getStringValue($fieldNode, 'lengthMessage')
            ?? $context->nodes->getStringValue($fieldNode, 'stringLengthMessage');
        if (!is_string($message) || $message === '') {
            return;
        }

        // Apply the same message for min / max / between cases.
        $schema->overrideErrorMessage(FlowValidationErrorCodes::STRING_LENGTH_BETWEEN, $message);
        $schema->overrideErrorMessage(FlowValidationErrorCodes::STRING_LENGTH_MIN, $message);
        $schema->overrideErrorMessage(FlowValidationErrorCodes::STRING_LENGTH_MAX, $message);
    }

    protected function applyPatternValidation(NeosContext $context, Node $fieldNode, SchemaDefinition $schema): void
    {
        $regularExpression = $context->nodes->getStringValue($fieldNode, 'regularExpression');
        if (is_string($regularExpression) && $regularExpression !== '') {
            $schema->validator('RegularExpression', [
                'regularExpression' => $this->normalizeRegularExpression($regularExpression),
            ]);
        }

        $useCustom = $context->nodes->getBoolValue($fieldNode, 'patternUseCustomMessage')
            ?? $context->nodes->getBoolValue($fieldNode, 'useCustomPatternMessage')
            ?? false;
        if (!$useCustom) {
            return;
        }

        $patternMessage = $context->nodes->getStringValue($fieldNode, 'patternMessage');
        if (is_string($patternMessage) && $patternMessage !== '') {
            $schema->overrideErrorMessage(FlowValidationErrorCodes::REGEX_MISMATCH, $patternMessage);
        }
    }

    protected function normalizeRegularExpression(string $pattern): string
    {
        return '/^' . $pattern . '$/';
    }
}
