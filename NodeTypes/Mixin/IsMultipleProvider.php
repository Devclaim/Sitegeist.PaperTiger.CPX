<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin;

use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\InspectorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\PropertyUiConfiguration;

#[NodeTypeDeclaration]
interface IsMultipleProvider
{
    #[PropertyUiConfiguration(
        label: 'i18n',
        reloadIfChanged: true,
    )]
    #[InspectorConfiguration(group: 'form')]
    public bool $isMultiple {get;}
}

