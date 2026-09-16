<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Property\PropertyMapper;
use Neos\Flow\Property\PropertyMappingConfiguration;
use Neos\Flow\Validation\Validator\DateTimeRangeValidator;
use Neos\Flow\Validation\ValidatorResolver;
use Sitegeist\PaperTiger\CPX\Domain\Validation\Validator\UploadedFileCollectionValidator;
use Sitegeist\PaperTiger\CPX\Domain\Validation\Validator\UploadedFileValidator;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\IsMultipleProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\DateRangeValidationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\NumberRangeValidationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\PatternValidationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\StringLengthValidationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\UploadValidationProvider;

final class FieldSchemaResolver
{
    public function __construct(
        protected readonly PropertyMapper $propertyMapper,
        protected readonly PropertyMappingConfiguration $propertyMappingConfiguration,
        protected readonly ValidatorResolver $validatorResolver,
    ) {
    }

    public function resolve(FormField $field): SchemaInterface
    {
        $schema = new SchemaDefinition(
            $this->propertyMapper,
            $this->propertyMappingConfiguration,
            $this->validatorResolver,
            $field->getSchemaTargetType(),
        );

        $schema = $field->requiresArrayOfSchema()
            ? new ArrayOfSchemaDefinition(
                $this->propertyMapper,
                $this->propertyMappingConfiguration,
                $this->validatorResolver,
                $schema,
            )
            : $schema;

        /** @todo move to interfaces / backing traits */
        if ($field instanceof RequiredValidationProvider && $field->isRequired) {
            $schema = $schema instanceof ArrayOfSchemaDefinition
                ? $schema->isRequired()
                : $schema->isRequiredWithId('required');

            if ($field->requiredUseCustomMessage && $field->requiredMessage) {
                $schema = $schema->overrideErrorMessages(FlowValidationErrorCodes::REQUIRED_CODES, $field->requiredMessage);
            }
        }

        if ($field instanceof DateRangeValidationProvider) {
            $options = array_filter([
                'earliestDate' => $field->earliestDate?->format('Y-m-d'),
                'latestDate' => $field->latestDate?->format('Y-m-d'),
            ]);

            if ($options !== []) {
                $schema = $schema->validatorWithId('dateRange', DateTimeRangeValidator::class, $options);

                if ($field->dateRangeUseCustomMessage && $field->dateRangeMessage) {
                    $schema->overrideErrorMessage(FlowValidationErrorCodes::DATE_TIME_RANGE_BETWEEN, $field->dateRangeMessage);
                    $schema->overrideErrorMessage(FlowValidationErrorCodes::DATE_TIME_RANGE_AFTER, $field->dateRangeMessage);
                    $schema->overrideErrorMessage(FlowValidationErrorCodes::DATE_TIME_RANGE_BEFORE, $field->dateRangeMessage);
                }
            }
        }

        if ($field instanceof NumberRangeValidationProvider) {
            $options = array_filter([
                'minimum' => $field->minimumValue,
                'maximum' => $field->maximumValue,
            ]);

            if ($options !== []) {
                $schema = $schema->validatorWithId('numberRange', 'NumberRange', $options);
            }
        }

        if ($field instanceof PatternValidationProvider && $field->regularExpression) {
            $schema->validatorWithId('pattern', 'RegularExpression', [
                'regularExpression' => '/^' . $field->regularExpression . '$/',
            ]);

            if ($field->patternUseCustomMessage && $field->patternMessage) {
                $schema->overrideErrorMessage(FlowValidationErrorCodes::REGEX_MISMATCH, $field->patternMessage);
            }
        }

        if ($field instanceof StringLengthValidationProvider) {
            $validatorApplied = false;

            if ($field->minimumLength !== null) {
                $schema->validatorWithId('minLength', 'StringLength', ['minimum' => $field->minimumLength]);
                $validatorApplied = true;
            }

            if ($field->maximumLength !== null) {
                $schema->validatorWithId('maxLength', 'StringLength', ['maximum' => $field->maximumLength]);
                $validatorApplied = true;
            }

            if ($validatorApplied && $field->lengthUseCustomMessage && $field->lengthMessage) {
                // Apply the same message for min / max / between cases.
                $schema->overrideErrorMessage(FlowValidationErrorCodes::STRING_LENGTH_BETWEEN, $field->lengthMessage);
                $schema->overrideErrorMessage(FlowValidationErrorCodes::STRING_LENGTH_MIN, $field->lengthMessage);
                $schema->overrideErrorMessage(FlowValidationErrorCodes::STRING_LENGTH_MAX, $field->lengthMessage);
            }
        }

        if ($field instanceof UploadValidationProvider) {
            $validatorClass = ($field instanceof IsMultipleProvider && $field->isMultiple)
                ? UploadedFileCollectionValidator::class
                : UploadedFileValidator::class;

            if ($field->allowedExtensions !== []) {
                $schema->validatorWithId('uploadType', $validatorClass, [
                    'allowedExtensions' => $field->allowedExtensions,
                    'maximumSize' => null,
                ]);
                if ($field->uploadTypeUseCustomMessage && $field->uploadTypeMessage) {
                    $schema->overrideErrorMessage(FlowValidationErrorCodes::UPLOAD_EXTENSION_NOT_ALLOWED, $field->uploadTypeMessage);
                }
            }

            if ($field->allowedFilesize !== null) {
                $schema->validatorWithId('uploadSize', $validatorClass, [
                    'allowedExtensions' => [],
                    'maximumSize' => $field->allowedFilesize,
                ]);
                if ($field->uploadSizeUseCustomMessage && $field->uploadSizeMessage) {
                    $schema->overrideErrorMessage(FlowValidationErrorCodes::UPLOAD_SIZE_EXCEEDED, $field->uploadSizeMessage);
                }
            }
        }

        return $schema;
    }
}
