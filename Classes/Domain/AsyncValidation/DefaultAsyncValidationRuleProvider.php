<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\AsyncValidation;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\Flow\I18n\Translator;
use PackageFactory\Neos\ComponentEngine\NodeAccessInterface;
use Sitegeist\PaperTiger\CPX\Domain\Validation\FieldSchemaResolver;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaDefinition;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\DateRangeValidationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\PatternValidationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\StringLengthValidationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\UploadValidationProvider;

final class DefaultAsyncValidationRuleProvider implements AsyncValidationRuleProviderInterface
{
    public function __construct(
        private readonly Translator $translator,
        private readonly FieldSchemaResolver $fieldSchemaProviderResolver,
    ) {
    }

    public function getPriority(): int
    {
        return -1000;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function forField(FormField $field): array
    {
        // We always need the name for the consumer; the factory will merge by field name.
        // Here we only return the validations.
        $validations = [];

        $schema = $this->fieldSchemaProviderResolver->resolve($field);
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
                'fieldName' => $field->name,
                'validationId' => $validationId,
                'options' => $options,
                'message' => $this->formatFallbackMessage(
                    $validationId,
                    $options,
                    $this->resolveAsyncMessage($field, $validationId)
                        ?: $this->translate('validationError.' . $validationId),
                ),
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

    private function resolveAsyncMessage(FormField $field, string $validationId): ?string
    {
        // Built-in validators still use legacy property names (because those mixins already exist).
        // Custom validators can use {validationId}UseCustomMessage + {validationId}Message.
        return match ($validationId) {
            'required' => $field instanceof RequiredValidationProvider && $field->requiredUseCustomMessage && $field->requiredMessage
                ? $field->requiredMessage
                : null,
            'minLength', 'maxLength' => $field instanceof StringLengthValidationProvider && $field->lengthUseCustomMessage && $field->lengthMessage
                ? $field->lengthMessage
                : null,
            'pattern' => $field instanceof PatternValidationProvider && $field->patternUseCustomMessage && $field->patternMessage
                ? $field->patternMessage
                : null,
            'dateRange' => $field instanceof DateRangeValidationProvider && $field->dateRangeUseCustomMessage && $field->dateRangeMessage
                ? $field->dateRangeMessage
                : null,
            'uploadType' => $field instanceof UploadValidationProvider && $field->uploadTypeUseCustomMessage && $field->uploadTypeMessage
                ? $field->uploadTypeMessage
                : null,
            'uploadSize' => $field instanceof UploadValidationProvider && $field->uploadSizeUseCustomMessage && $field->uploadSizeMessage
                ? $field->uploadSizeMessage
                : null,
            default => $field instanceof CustomErrorMessageProvider && $field->customErrorMessageEnabled && $field->customErrorMessage
                ? $field->customErrorMessage
                : null,
        };
    }

    /**
     * Some translated fallback messages contain placeholders (e.g. "%d") that need to be formatted with schema options.
     *
     * Custom messages (from the inspector) are assumed to be pre-formatted by the editor and are not altered.
     *
     * @param array<string,mixed> $options
     */
    private function formatFallbackMessage(string $validationId, array $options, string $message): string
    {
        if ($message === '') {
            return $message;
        }

        try {
            return match ($validationId) {
                'minLength' => (isset($options['minimum']) && is_int($options['minimum']))
                    ? sprintf($message, $options['minimum'])
                    : $message,
                'maxLength' => (isset($options['maximum']) && is_int($options['maximum']))
                    ? sprintf($message, $options['maximum'])
                    : $message,
                'uploadSize' => (isset($options['maximumSize']) && is_int($options['maximumSize']))
                    ? sprintf($message, $options['maximumSize'])
                    : $message,
                default => $message,
            };
        } catch (\Throwable) {
            // If formatting fails (mismatched placeholders), fall back to the raw message.
            return $message;
        }
    }
}
