<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation;

use Neos\Error\Messages\Result;

final class SchemaCollectionDefinition implements SchemaInterface
{
    /**
     * @param array<string, SchemaInterface> $schemas
     */
    public function __construct(
        private readonly array $schemas,
    ) {
    }

    public function validate(mixed $data): Result
    {
        $result = new Result();

        if (!is_array($data)) {
            $result->addError(new \Neos\Error\Messages\Error('The submitted value must be an array.', 1744721001));
            return $result;
        }

        foreach ($this->schemas as $key => $schema) {
            $result->forProperty($key)->merge($schema->validate($data[$key] ?? null));
        }

        return $result;
    }

    public function convert(mixed $data): mixed
    {
        if (!is_array($data)) {
            return null;
        }

        $result = [];
        foreach ($this->schemas as $key => $schema) {
            $result[$key] = $schema->convert($data[$key] ?? null);
        }

        return $result;
    }
}