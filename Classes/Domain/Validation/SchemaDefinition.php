<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation;

use Neos\Error\Messages\Result;
use Neos\Flow\Property\PropertyMapper;
use Neos\Flow\Property\PropertyMappingConfiguration;
use Neos\Flow\Validation\Validator\NotEmptyValidator;
use Neos\Flow\Validation\ValidatorResolver;

class SchemaDefinition implements SchemaInterface
{
    /**
     * @var array<int, array{type: string, options: array<string, mixed>|null}>
     */
    protected array $validators = [];

    /**
     * @var array<int, array{class: string, option: string, value: mixed}>
     */
    protected array $typeConverterOptions = [];

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
            'type' => $type,
            'options' => $options,
        ];

        return $this;
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

    public function validate(mixed $data): Result
    {
        $result = new Result();

        foreach ($this->validators as $validationConfiguration) {
            $validator = $this->validatorResolver->createValidator(
                $validationConfiguration['type'],
                $validationConfiguration['options'] ?? []
            );

            if ($validator === null) {
                throw new \RuntimeException('Validator could not get created.', 1744410020);
            }

            $result->merge($validator->validate($data));
        }

        return $result;
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
