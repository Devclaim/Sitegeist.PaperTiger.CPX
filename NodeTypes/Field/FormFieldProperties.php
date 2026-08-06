<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field;

use Neos\Neos\NodeTypes\ContentProperties;

/**
 * backing trait for {@see FormField}
 */
trait FormFieldProperties
{
    use ContentProperties;

    public readonly string $name;

    public function getLabel(): string
    {
        return ($this->isRequired ? '*' : '') . ($this->label ?? $this->name);
    }
}
