<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation;

use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\InspectorGroupDeclaration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\TextAreaEditor\TextAreaEditorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\InspectorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\PropertyUiConfiguration;

#[NodeTypeDeclaration]
#[InspectorGroupDeclaration(
    name: 'form-validation-required',
    label: 'Sitegeist.PaperTiger.CPX:Main:validation.required.group',
    icon: 'icon-asterisk',
    position: '10',
    tab: 'form-validation',
)]
interface RequiredValidationProvider
{
    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.required.isRequired',
        reloadIfChanged: true,
    )]
    #[InspectorConfiguration(group: 'form-validation-required', position: 10)]
    public bool $isRequired {get;}

    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.useCustomMessage',
        reloadIfChanged: true,
    )]
    #[InspectorConfiguration(group: 'form-validation-required', position: 15)]
    public bool $requiredUseCustomMessage {get;}

    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.required.message',
        reloadIfChanged: true,
    )]
    #[InspectorConfiguration(
        group: 'form-validation-required',
        position: 20,
        hidden: 'ClientEval:node.properties.requiredUseCustomMessage ? false : true'
    )]
    #[TextAreaEditorConfiguration(
        rows: 3,
    )]
    public ?string $requiredMessage {get;}
}

