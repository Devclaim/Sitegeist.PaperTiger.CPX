<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation;

use Neos\Error\Messages\Error;
use Neos\Error\Messages\Result;
use Neos\Flow\Property\PropertyMapper;
use Neos\Flow\Property\PropertyMappingConfiguration;
use Neos\Flow\Validation\Validator\NotEmptyValidator;
use Neos\Flow\Validation\ValidatorResolver;

class SchemaDefinition implements SchemaInterface
{
    /**
     * @var array<int, array{id: ?string, type: string, options: array<string, mixed>|null}>
     */
    protected array $validators = [];

    /**
     * @var array<int, array{class: string, option: string, value: mixed}>
     */
    protected array $typeConverterOptions = [];

    /**
     * Override Flow validation messages by error code.
     *
     * @var array<int, string>
     */
    protected array $errorMessageOverrides = [];

    public function __construct(
        protected readonly PropertyMapper $propertyMapper,
        protected readonly PropertyMappingConfiguration $propertyMappingConfiguration,
        protected readonly ValidatorResolver $validatorResolver,
        protected string $targetType = 'string',
        array $validators = [],
        array $typeConverterOptions = [],
    ) {
        $this->validators = $validators;
        $this->typeConverterOptions = $typeConverterOptions;
    }

    /**
     * @param array<string, mixed>|null $options
     */
    public function validator(string $type, ?array $options = null): static
    {
        $this->validators[] = [
            'id' => null,
            'type' => $type,
            'options' => $options,
        ];

        return $this;
    }

    /**
     * Add a validator with a stable id that will be exposed to async clients.
     *
     * @param array<string, mixed>|null $options
     */
    public function validatorWithId(string $validationId, string $type, ?array $options = null): static
    {
        $validationId = trim($validationId);
        $this->validators[] = [
            'id' => $validationId !== '' ? $validationId : null,
            'type' => $type,
            'options' => $options,
        ];

        return $this;
    }

    /**
     * Used by async validation descriptor generation.
     *
     * @return list<array{id: ?string, type: string, options: array<string, mixed>|null}>
     */
    public function getValidators(): array
    {
        return $this->validators;
    }

    public function typeConverterOption(string $className, string $optionName, mixed $optionValue): static
    {
        $this->typeConverterOptions[] = [
            'class' => $className,
            'option' => $optionName,
            'value' => $optionValue,
        ];

        return $this;
    }

    public function isRequired(): static
    {
        return $this->validator(NotEmptyValidator::class);
    }

    public function isRequiredWithId(string $validationId = 'required'): static
    {
        return $this->validatorWithId($validationId, NotEmptyValidator::class);
    }

    /**
     * Override a Flow validator error message by its error code.
     */
    public function overrideErrorMessage(int $code, string $message): static
    {
        $message = trim($message);
        if ($message === '') {
            return $this;
        }

        $this->errorMessageOverrides[$code] = $message;
        return $this;
    }

    /**
     * @param array<int> $codes
     */
    public function overrideErrorMessages(array $codes, string $message): static
    {
        foreach ($codes as $code) {
            if (is_int($code)) {
                $this->overrideErrorMessage($code, $message);
            }
        }
        return $this;
    }

    public function validate(mixed $data): Result
    {
        $result = new Result();

        // If nothing was submitted, we only validate "required" and skip all other validators.
        // This prevents validators like DateTimeRangeValidator from complaining about empty strings.
        if ($this->isEmptySubmittedValue($data)) {
            foreach ($this->validators as $validationConfiguration) {
                if ($validationConfiguration['type'] !== NotEmptyValidator::class) {
                    continue;
                }

                $validator = $this->validatorResolver->createValidator(
                    $validationConfiguration['type'],
                    $validationConfiguration['options'] ?? []
                );
                if ($validator === null) {
                    throw new \RuntimeException('Validator could not get created.', 1744410020);
                }

                $validatorResult = $validator->validate($data);
                $this->mergeValidatorResult(
                    $result,
                    $validatorResult,
                    $validationConfiguration['id']
                );
            }

            return $result;
        }

        // If a value can be converted we validate the converted value. Otherwise we validate the raw input.
        $convertedValue = $this->convert($data);
        $valueToValidate = $convertedValue ?? $data;

        foreach ($this->validators as $validationConfiguration) {
            $validator = $this->validatorResolver->createValidator(
                $validationConfiguration['type'],
                $validationConfiguration['options'] ?? []
            );

            if ($validator === null) {
                throw new \RuntimeException('Validator could not get created.', 1744410020);
            }

            $validatorResult = $validator->validate($valueToValidate);
            $this->mergeValidatorResult(
                $result,
                $validatorResult,
                $validationConfiguration['id']
            );
        }

        return $result;
    }

    private function mergeValidatorResult(Result $into, Result $from, ?string $validationId): void
    {
        foreach ($from->getErrors() as $error) {
            $override = $this->errorMessageOverrides[$error->getCode()] ?? null;
            $message = (is_string($override) && $override !== '') ? $override : $error->getMessage();

            $into->addError(new ValidationError(
                $message,
                $error->getCode(),
                $validationId,
            ));
        }
    }

    private function isEmptySubmittedValue(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }

        if (is_array($value)) {
            return $value === [];
        }

        if ($value instanceof \Psr\Http\Message\UploadedFileInterface) {
            return $value->getError() === UPLOAD_ERR_NO_FILE;
        }

        return false;
    }

    public function convert(mixed $data): mixed
    {
        $propertyMappingConfiguration = clone $this->propertyMappingConfiguration;

        foreach ($this->typeConverterOptions as $typeConverterOption) {
            $propertyMappingConfiguration->setTypeConverterOption(
                $typeConverterOption['class'],
                $typeConverterOption['option'],
                $typeConverterOption['value']
            );
        }

        try {
            $mappedValue = $this->propertyMapper->convert($data, $this->targetType, $propertyMappingConfiguration);
        } catch (\Throwable) {
            // Converters are optional; if no converter exists (e.g. string -> UploadedFileInterface),
            // we treat the value as "not convertible" and let the validators handle the raw input.
            return null;
        }

        $mappingResult = $this->propertyMapper->getMessages();

        if ($mappingResult->hasErrors()) {
            return null;
        }

        return $mappedValue;
    }
}
