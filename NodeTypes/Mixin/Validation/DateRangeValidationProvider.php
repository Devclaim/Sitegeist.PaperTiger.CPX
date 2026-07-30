<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation;

use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\InspectorGroupDeclaration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\DateTimeEditor\DateTimeEditorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\TextAreaEditor\TextAreaEditorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\InspectorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\PropertyUiConfiguration;

#[NodeTypeDeclaration]
#[InspectorGroupDeclaration(
    name: 'form-validation-date-range',
    label: 'Sitegeist.PaperTiger.CPX:Main:validation.dateRange.group',
    icon: 'icon-calendar',
    position: '60',
    tab: 'form-validation',
)]
interface DateRangeValidationProvider
{
    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.dateRange.earliestDate',
    )]
    #[InspectorConfiguration(group: 'form-validation-date-range')]
    #[DateTimeEditorConfiguration(format: 'd.m.Y')]
    public ?\DateTimeImmutable $earliestDate {get;}

    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.dateRange.latestDate',
    )]
    #[InspectorConfiguration(group: 'form-validation-date-range')]
    #[DateTimeEditorConfiguration(format: 'd.m.Y')]
    public ?\DateTimeImmutable $latestDate {get;}

    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.useCustomMessage',
    )]
    #[InspectorConfiguration(group: 'form-validation-date-range', position: '30')]
    public bool $dateRangeUseCustomMessage {get;}

    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.dateRange.message',
    )]
    #[InspectorConfiguration(
        group: 'form-validation-date-range',
        position: '40',
        hidden: 'ClientEval:node.properties.dateRangeUseCustomMessage ? false : true',
    )]
    #[TextAreaEditorConfiguration(rows: 3)]
    public ?string $dateRangeMessage {get;}
}

