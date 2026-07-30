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
    name: 'form-validation-length',
    label: 'Sitegeist.PaperTiger.CPX:Main:validation.length.group',
    icon: 'icon-text-width',
    position: '20',
    tab: 'form-validation',
)]
interface StringLengthValidationProvider
{
    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.length.minimum',
    )]
    #[InspectorConfiguration(
        group: 'form-validation-length',
        position: 10,
    )]
    public ?int $minimumLength {get;}

    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.length.maximum',
    )]
    #[InspectorConfiguration(
        group: 'form-validation-length',
        position: 20,
    )]
    public ?int $maximumLength {get;}

    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.useCustomMessage',
    )]
    #[InspectorConfiguration(
        group: 'form-validation-length',
        position: 25,
    )]
    public bool $lengthUseCustomMessage {get;}

    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.length.message',
    )]
    #[InspectorConfiguration(
        group: 'form-validation-length',
        position: 30,
        hidden: 'ClientEval:node.properties.lengthUseCustomMessage ? false : true',
    )]
    #[TextAreaEditorConfiguration(rows: 3)]
    public ?string $lengthMessage {get;}
}

