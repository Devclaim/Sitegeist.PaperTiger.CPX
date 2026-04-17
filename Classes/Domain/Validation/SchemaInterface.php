<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation;

use Neos\Error\Messages\Result;

interface SchemaInterface
{
    public function validate(mixed $data): Result;

    public function convert(mixed $data): mixed;
}