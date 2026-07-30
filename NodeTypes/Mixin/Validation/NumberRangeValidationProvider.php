<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation;

use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\InspectorGroupDeclaration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\InspectorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\PropertyUiConfiguration;

#[NodeTypeDeclaration]
#[InspectorGroupDeclaration(
    name: 'form-validation-number-range',
    label: 'Sitegeist.PaperTiger.CPX:Main:validation.numberRange.group',
    icon: 'icon-sort-numeric-up-alt',
    position: '65',
    tab: 'form-validation',
)]
interface NumberRangeValidationProvider
{
    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.dateRange.earliestDate',
    )]
    #[InspectorConfiguration(group: 'form-validation-number-range')]
    public ?int $minimumValue {get;}

    #[PropertyUiConfiguration(label: 'i18n')]
    #[InspectorConfiguration(group: 'form-validation-number-range')]
    public ?int $maximumValue {get;}
}

