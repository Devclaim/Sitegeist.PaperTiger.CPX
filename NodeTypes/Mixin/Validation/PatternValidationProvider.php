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
    name: 'form-validation-pattern',
    label: 'Sitegeist.PaperTiger.CPX:Main:validation.pattern.group',
    icon: 'icon-filter',
    position: '30',
    tab: 'form-validation',
)]
interface PatternValidationProvider
{
    #[PropertyUiConfiguration(label: 'Sitegeist.PaperTiger.CPX:Main:validation.pattern.regularExpression')]
    #[InspectorConfiguration(group: 'form-validation-pattern', position: 10)]
    public ?string $regularExpression {get;}

    #[PropertyUiConfiguration(label: 'Sitegeist.PaperTiger.CPX:Main:validation.useCustomMessage')]
    #[InspectorConfiguration(group: 'form-validation-pattern', position: 15)]
    public bool $patternUseCustomMessage {get;}

    #[PropertyUiConfiguration(label: 'Sitegeist.PaperTiger.CPX:Main:validation.pattern.message')]
    #[InspectorConfiguration(
        group: 'form-validation-pattern',
        position: 20,
        hidden: 'ClientEval:node.properties.patternUseCustomMessage ? false : true',
    )]
    #[TextAreaEditorConfiguration(rows: 3)]
    public ?string $patternMessage {get;}
}

