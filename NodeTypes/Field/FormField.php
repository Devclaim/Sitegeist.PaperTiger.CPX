<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field;

use Neos\Neos\NodeTypes\Content;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\InspectorGroupDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\InspectorTabDeclaration;

#[NodeTypeDeclaration(
    label: '${(q(node).property("isRequired") ? "*" : "") + Neos.Node.labelForNode(node).properties("label", "name")}'
)]
#[InspectorTabDeclaration(
    name: 'form-validation',
    label: 'Sitegeist.PaperTiger.CPX:Main:validation.tab',
    icon: 'check-double',
    position: '11',
)]
#[InspectorGroupDeclaration(
    name: 'form',
    label: 'i18n',
    icon: 'icon-list-alt',
    position: '30'
)]
#[InspectorGroupDeclaration(
    name: 'form-validation',
    label: 'i18n',
    icon: 'check-double',
    position: '40'
)]
#[InspectorGroupDeclaration(
    name: 'form-error',
    label: 'i18n',
    icon: 'icon-list-alt',
    position: '50'
)]
interface FormField extends Content, FieldConstraint, NameProvider
{
}

