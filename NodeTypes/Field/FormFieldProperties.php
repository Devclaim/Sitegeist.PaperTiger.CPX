<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field;

use Neos\Neos\NodeTypes\ContentProperties;
use PackageFactory\OPGM\NeosAdapter\Infrastructure\NodeLabelRenderingAccessInterface;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\LabelProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProvider;

/**
 * backing trait for {@see FormField}
 */
trait FormFieldProperties
{
    use ContentProperties;

    public readonly string $name;

    public function getNeosLabel(NodeLabelRenderingAccessInterface $nodeLabelRenderingAccess): string
    {
        $prefix = $this instanceof RequiredValidationProvider ? ($this->isRequired ? '*' : '') : '';
        $label = $this instanceof LabelProvider ? $this->label : $this->name;

        return $prefix . $label;
    }
}
