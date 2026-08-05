<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Dropdown;

use Neos\Flow\Annotations as Flow;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormFieldProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Field\DropdownProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Field\DropdownProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\IsMultipleProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\LabelProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProvider;

#[NodeTypeDeclaration]
#[NodeTypeUiConfiguration(
    label: 'i18n',
    icon: 'icon-list-ol',
    group: 'form.elements',
    position: 600,
)]
#[Flow\Proxy(false)]
readonly class Dropdown implements
    FormField,
    LabelProvider,
    IsMultipleProvider,
    RequiredValidationProvider,
    CustomErrorMessageProvider,
    DropdownProvider
{
    use FormFieldProperties;
    use RequiredValidationProperties;
    use CustomErrorMessageProperties;
    use DropdownProperties;

    public function __construct(
        public string $label,
        public bool $isMultiple = false,
    ) {
    }
}
