<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\TelephoneNumber;

use Neos\Flow\Annotations as Flow;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormFieldProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\PlaceholderProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\LabelProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\NumberRangeValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\PatternValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\PatternValidationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProvider;

#[NodeTypeDeclaration]
#[NodeTypeUiConfiguration(
    label: 'i18n',
    icon: 'icon-phone',
    group: 'form.elements',
    position: 400,
)]
#[Flow\Proxy(false)]
readonly class TelephoneNumber implements
    FormField,
    LabelProvider,
    PlaceholderProvider,
    RequiredValidationProvider,
    PatternValidationProvider,
    CustomErrorMessageProvider
{
    use FormFieldProperties;
    use RequiredValidationProperties;
    use NumberRangeValidationProperties;
    use PatternValidationProperties;
    use CustomErrorMessageProperties;

    public function __construct(
        public string $label,
        public ?string $placeholder,
    ) {
    }
}
