<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation;

use Neos\Error\Messages\Result;
use Neos\Flow\Property\PropertyMapper;
use Neos\Flow\Property\PropertyMappingConfiguration;
use Neos\Flow\Validation\ValidatorResolver;

class ArrayOfSchemaDefinition extends SchemaDefinition
{
    public function __construct(
        PropertyMapper $propertyMapper,
        PropertyMappingConfiguration $propertyMappingConfiguration,
        ValidatorResolver $validatorResolver,
        protected readonly ?SchemaInterface $itemSchema = null,
        array $validators = [],
        array $typeConverterOptions = [],
    ) {
        parent::__construct(
            $propertyMapper,
            $propertyMappingConfiguration,
            $validatorResolver,
            'array',
            $validators,
            $typeConverterOptions,
        );
    }

    public function validate(mixed $data): Result
    {
        $result = parent::validate($data);

        if (!is_array($data) || $this->itemSchema === null) {
            return $result;
        }

        foreach ($data as $key => $value) {
            $result->forProperty((string)$key)->merge($this->itemSchema->validate($value));
        }

        return $result;
    }

    public function convert(mixed $data): mixed
    {
        $arrayData = parent::convert($data);

        if (!is_array($arrayData) || $this->itemSchema === null) {
            return $arrayData;
        }

        $result = [];
        foreach ($arrayData as $key => $value) {
            $result[$key] = $this->itemSchema->convert($value);
        }

        return $result;
    }
}