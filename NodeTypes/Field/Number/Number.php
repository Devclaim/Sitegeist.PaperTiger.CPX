<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Number;

use Neos\Flow\Annotations as Flow;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\CustomErrorMessageProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormFieldProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\PlaceholderProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\LabelProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\NumberRangeValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\NumberRangeValidationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProvider;

#[NodeTypeDeclaration]
#[NodeTypeUiConfiguration(
    label: 'i18n',
    icon: 'icon-sort-numeric-up-alt',
    group: 'form.elements',
    position: 1100,
)]
#[Flow\Proxy(false)]
readonly class Number implements
    FormField,
    LabelProvider,
    PlaceholderProvider,
    RequiredValidationProvider,
    NumberRangeValidationProvider,
    CustomErrorMessageProvider
{
    use FormFieldProperties;
    use RequiredValidationProperties;
    use NumberRangeValidationProperties;
    use CustomErrorMessageProperties;

    public function __construct(
        public string $label,
        public ?string $placeholder,
    ) {
    }
}
