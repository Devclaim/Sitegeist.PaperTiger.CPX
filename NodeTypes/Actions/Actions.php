<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Actions;

use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\InspectorGroupDeclaration;

#[NodeTypeDeclaration]
#[InspectorGroupDeclaration(
    name: 'actions-message',
    label: 'Sitegeist.PaperTiger.CPX:NodeTypes.Action:groups.message',
    icon: 'commenting-o',
    position: '20',
)]
#[InspectorGroupDeclaration(
    name: 'actions-email',
    label: 'Sitegeist.PaperTiger.CPX:NodeTypes.Action:groups.email',
    icon: 'icon-envelope-o',
    position: '30',
)]
#[InspectorGroupDeclaration(
    name: 'actions-email',
    label: 'Sitegeist.PaperTiger.CPX:NodeTypes.Action:groups.email',
    icon: 'icon-envelope-o',
    position: '30',
)]
interface Actions
{
}
