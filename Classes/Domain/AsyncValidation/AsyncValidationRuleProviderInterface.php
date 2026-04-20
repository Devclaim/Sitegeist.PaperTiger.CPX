<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\AsyncValidation;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use PackageFactory\Neos\ComponentEngine\NeosContext;

interface AsyncValidationRuleProviderInterface
{
    public function getPriority(): int;

    /**
     * Return async validation rules for a single field.
     *
     * @return list<array<string, mixed>>
     */
    public function forField(NeosContext $context, Node $fieldNode): array;
}

