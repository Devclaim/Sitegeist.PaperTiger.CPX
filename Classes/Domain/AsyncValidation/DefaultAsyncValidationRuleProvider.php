<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\AsyncValidation;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\Flow\I18n\Translator;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\Schema\FieldSchemaProviderResolver;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaDefinition;

final class DefaultAsyncValidationRuleProvider implements AsyncValidationRuleProviderInterface
{
    public function __construct(
        private readonly Translator $translator,
        private readonly FieldSchemaProviderResolver $fieldSchemaProviderResolver,
    ) {
    }

    public function getPriority(): int
    {
        return -1000;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function forField(NeosContext $context, Node $fieldNode): array
    {
        $name = $context->nodes->getStringValue($fieldNode, 'name') ?? $fieldNode->aggregateId->value;

        // We always need the name for the consumer; the factory will merge by field name.
        // Here we only return the validations.
        $validations = [];

        $schema = $this->fieldSchemaProviderResolver->resolve($context, $fieldNode);
        if (!$schema instanceof SchemaDefinition) {
            return [];
        }

        foreach ($schema->getValidators() as $validator) {
            $validationId = $validator['id'] ?? null;
            if (!is_string($validationId) || $validationId === '') {
                continue;
            }

            $options = is_array($validator['options'] ?? null) ? $validator['options'] : [];

            $validations[] = [
                'fieldName' => $name,
                'validationId' => $validationId,
                'options' => $options,
                'message' => $this->resolveAsyncMessage($context, $fieldNode, $validationId)
                    ?? $this->translate('validationError.' . $validationId),
            ];
        }

        return $validations;
    }

    private function translate(string $id): string
    {
        $value = $this->translator->translateById(
            $id,
            [],
            null,
            null,
            'Main',
            'Sitegeist.PaperTiger.CPX',
        );

        return is_string($value) && $value !== '' ? $value : $id;
    }

    private function customMessage(
        NeosContext $context,
        Node $fieldNode,
        string $newFlag,
        string $legacyFlag,
        string $message,
        ?string $legacyMessage = null,
    ): ?string {
        $useCustom = $context->nodes->getBoolValue($fieldNode, $newFlag)
            ?? $context->nodes->getBoolValue($fieldNode, $legacyFlag)
            ?? false;

        if (!$useCustom) {
            return null;
        }

        $value = $context->nodes->getStringValue($fieldNode, $message);
        if ((!is_string($value) || $value === '') && $legacyMessage !== null) {
            $value = $context->nodes->getStringValue($fieldNode, $legacyMessage);
        }

        return is_string($value) && $value !== '' ? $value : null;
    }

    private function resolveAsyncMessage(NeosContext $context, Node $fieldNode, string $validationId): ?string
    {
        // Built-in validators still use legacy property names (because those mixins already exist).
        // Custom validators can use {validationId}UseCustomMessage + {validationId}Message.
        return match ($validationId) {
            'required' => $this->customMessage($context, $fieldNode, 'requiredUseCustomMessage', 'useCustomRequiredMessage', 'requiredMessage'),
            'minLength', 'maxLength' => $this->customMessage($context, $fieldNode, 'lengthUseCustomMessage', 'useCustomStringLengthMessage', 'lengthMessage', 'stringLengthMessage'),
            'pattern' => $this->customMessage($context, $fieldNode, 'patternUseCustomMessage', 'useCustomPatternMessage', 'patternMessage'),
            'dateRange' => $this->customMessage($context, $fieldNode, 'dateRangeUseCustomMessage', 'useCustomDateRangeMessage', 'dateRangeMessage'),
            'uploadType' => $this->customMessage($context, $fieldNode, 'uploadTypeUseCustomMessage', 'useCustomUploadTypeMessage', 'uploadTypeMessage'),
            'uploadSize' => $this->customMessage($context, $fieldNode, 'uploadSizeUseCustomMessage', 'useCustomUploadSizeMessage', 'uploadSizeMessage'),
            default => $this->customMessage(
                $context,
                $fieldNode,
                $validationId . 'UseCustomMessage',
                'useCustom' . ucfirst($validationId) . 'Message',
                $validationId . 'Message',
            )
        };
    }
}
