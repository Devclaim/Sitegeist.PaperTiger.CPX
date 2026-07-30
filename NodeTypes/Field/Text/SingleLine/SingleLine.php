<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Text\SingleLine;

use Neos\Flow\Annotations as Flow;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\CustomErrorMessageProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormFieldProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\PlaceholderProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\LabelProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\PatternValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\PatternValidationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\StringLengthValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\StringLengthValidationProvider;

#[NodeTypeDeclaration]
#[NodeTypeUiConfiguration(
    label: 'i18n',
    icon: 'icon-pencil-square-o',
    group: 'form.elements',
    position: 100,
)]
#[Flow\Proxy(false)]
readonly class SingleLine implements
    FormField,
    LabelProvider,
    PlaceholderProvider,
    RequiredValidationProvider,
    StringLengthValidationProvider,
    PatternValidationProvider,
    CustomErrorMessageProvider
{
    use FormFieldProperties;
    use RequiredValidationProperties;
    use StringLengthValidationProperties;
    use PatternValidationProperties;
    use CustomErrorMessageProperties;

    public function __construct(
        public string $label,
        public ?string $placeholder,
    ) {
    }
}
