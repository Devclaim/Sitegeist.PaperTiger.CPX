<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Altcha;

use Neos\Flow\Annotations as Flow;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeConstraintsDeclaration;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldConstraint;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormFieldProperties;

#[NodeTypeDeclaration(
    new NodeTypeConstraintsDeclaration(fqns: [
        FieldConstraint::class => true,
    ]),
)]
#[NodeTypeUiConfiguration(
    label: 'Altcha (Captcha)',
    icon: 'wrench',
    group: 'form.special',
    position: 10,
)]
#[Flow\Proxy(false)]
readonly class Altcha implements FormField
{
    use FormFieldProperties;
}
