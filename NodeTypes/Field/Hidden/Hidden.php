<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Hidden;

use Neos\Flow\Annotations as Flow;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\InspectorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\PropertyUiConfiguration;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormFieldProperties;

#[NodeTypeDeclaration]
#[NodeTypeUiConfiguration(
    label: 'i18n',
    icon: 'eye-slash',
    group: 'form.special',
)]
#[Flow\Proxy(false)]
readonly class Hidden implements FormField
{
    use FormFieldProperties;

    public function __construct(
        #[PropertyUiConfiguration(label: 'i18n')]
        #[InspectorConfiguration(group: 'form')]
        public string $value,
    ) {
    }
}
