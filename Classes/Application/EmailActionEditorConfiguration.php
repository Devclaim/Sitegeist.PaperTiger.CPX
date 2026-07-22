<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Application;

use PackageFactory\OPGM\Domain\Property\PropertyConfigurationInterface;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final readonly class EmailActionEditorConfiguration implements PropertyConfigurationInterface
{
    public function getPropertyConfiguration(\ReflectionNamedType $propertyType): array
    {
        return ['ui' => ['inspector' => [
            'editor' => 'Sitegeist.PaperTiger.CPX/Inspector/Editors/EmailActionEditor',
        ]]];
    }
}