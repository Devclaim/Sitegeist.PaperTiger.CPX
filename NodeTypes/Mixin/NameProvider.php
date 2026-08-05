<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin;

use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\HelpOptions;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\InspectorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\PropertyUiConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Validator\RegularExpressionValidatorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Validator\StringLengthValidatorConfiguration;

#[NodeTypeDeclaration]
interface NameProvider
{
    #[PropertyUiConfiguration(
        label: 'i18n',
        reloadIfChanged: true,
        help: new HelpOptions(
            message: 'i18n',
        ),
    )]
    #[InspectorConfiguration(group: 'form')]
    #[StringLengthValidatorConfiguration(minimum: 1, maximum: 255)]
    #[RegularExpressionValidatorConfiguration(regularExpression: '/^[a-z0-9\-]+$/i')]
    public string $name {get;}
}

