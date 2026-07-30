<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation;

/**
 * backing trait for {@see NumberRangeValidationProvider}
 */
trait NumberRangeValidationProperties
{
    public readonly ?int $minimumValue;

    public readonly ?int $maximumValue;
}
