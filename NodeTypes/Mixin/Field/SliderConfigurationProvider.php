<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Field;

use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\InspectorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\PropertyUiConfiguration;

#[NodeTypeDeclaration]
interface SliderConfigurationProvider
{
    #[PropertyUiConfiguration(
        label: 'i18n',
        reloadIfChanged: true,
        showInCreationDialog: true,
    )]
    #[InspectorConfiguration(group: 'form')]
    public int $minimumValue {get;}

    #[PropertyUiConfiguration(
        label: 'i18n',
        reloadIfChanged: true,
        showInCreationDialog: true,
    )]
    #[InspectorConfiguration(group: 'form')]
    public int $maximumValue {get;}

    #[PropertyUiConfiguration(
        label: 'i18n',
        reloadIfChanged: true,
    )]
    #[InspectorConfiguration(group: 'form')]
    public int $stepValue {get;}
}

