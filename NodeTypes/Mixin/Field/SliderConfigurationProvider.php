<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Field;

use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\InspectorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\PropertyUiConfiguration;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\NumberRangeValidationProvider;

#[NodeTypeDeclaration]
interface SliderConfigurationProvider extends NumberRangeValidationProvider
{
    #[PropertyUiConfiguration(
        label: 'i18n',
        reloadIfChanged: true,
    )]
    #[InspectorConfiguration(group: 'form')]
    public int $stepValue {get;}
}

