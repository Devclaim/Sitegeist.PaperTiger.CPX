<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Field;

use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\InspectorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\PropertyUiConfiguration;

#[NodeTypeDeclaration]
#[NodeTypeUiConfiguration(
    label: 'i18n',
    icon: 'icon-list-ol',
    group: 'form.elements',
    position: 600,
)]
interface DropdownProvider
{
    #[PropertyUiConfiguration(
        label: 'i18n',
        reloadIfChanged: true,
    )]
    #[InspectorConfiguration(
        group: 'form',
        position: 'before options',
    )]
    public bool $emptyOptionEnabled {get;}

    #[PropertyUiConfiguration(
        label: 'i18n',
        reloadIfChanged: true,
    )]
    #[InspectorConfiguration(
        group: 'form',
        position: 'after emptyOptionEnabled',
        hidden: 'ClientEval:node.properties.emptyOptionEnabled ? false : true',
    )]
    public ?string $emptyLabel {get;}
}

