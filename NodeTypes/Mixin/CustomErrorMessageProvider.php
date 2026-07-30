<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin;

use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\InspectorGroupDeclaration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\TextAreaEditor\TextAreaEditorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\InspectorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\PropertyUiConfiguration;

#[NodeTypeDeclaration]
#[InspectorGroupDeclaration(
    name: 'form-validation-popup',
    label: 'Sitegeist.PaperTiger.CPX:Main:validation.popup.group',
    icon: 'icon-comment-alt',
    position: 'end',
    tab: 'form-validation',
)]
interface CustomErrorMessageProvider
{
    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.popup.useCustomMessage',
    )]
    #[InspectorConfiguration(group: 'form-validation-popup', position: 10)]
    public bool $customErrorMessageEnabled {get;}

    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.popup.message',
    )]
    #[InspectorConfiguration(
        group: 'form-validation-popup',
        position: 20,
        hidden: 'ClientEval:node.properties.customErrorMessageEnabled ? false : true',
    )]
    #[TextAreaEditorConfiguration(rows: 7)]
    public ?string $customErrorMessage {get;}
}

