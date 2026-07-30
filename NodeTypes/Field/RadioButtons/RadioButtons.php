<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\RadioButtons;

use Neos\Flow\Annotations as Flow;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\CustomErrorMessageProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormFieldProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\LabelProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProvider;

#[NodeTypeDeclaration]
#[Flow\Proxy(false)]
readonly class RadioButtons implements
    FormField,
    LabelProvider,
    RequiredValidationProvider,
    CustomErrorMessageProvider
{
    use FormFieldProperties;
    use RequiredValidationProperties;
    use CustomErrorMessageProperties;

    public function __construct(
        public string $label,
    ) {
    }
}
