<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Date;

use Neos\Flow\Annotations as Flow;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\CustomErrorMessageProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormFieldProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\PlaceholderProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\LabelProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\DateRangeValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\DateRangeValidationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProvider;

#[NodeTypeDeclaration]
#[NodeTypeUiConfiguration(
    label: 'i18n',
    icon: 'icon-calendar',
    group: 'form.elements',
    position: 500,
)]
#[Flow\Proxy(false)]
readonly class Date implements
    FormField,
    LabelProvider,
    RequiredValidationProvider,
    CustomErrorMessageProvider,
    PlaceholderProvider,
    DateRangeValidationProvider
{
    use FormFieldProperties;
    use RequiredValidationProperties;
    use CustomErrorMessageProperties;
    use DateRangeValidationProperties;

    public function __construct(
        public string $label,
        public ?string $placeholder,
    ) {
    }
}
