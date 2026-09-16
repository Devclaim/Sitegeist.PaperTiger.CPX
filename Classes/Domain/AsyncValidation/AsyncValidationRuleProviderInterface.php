<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\AsyncValidation;

use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;

interface AsyncValidationRuleProviderInterface
{
    public function getPriority(): int;

    /**
     * Return async validation rules for a single field.
     *
     * @return list<array<string, mixed>>
     */
    public function forField(FormField $field): array;
}

