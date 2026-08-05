<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Email;

use Neos\Flow\Annotations as Flow;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormFieldProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\PlaceholderProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\LabelProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProvider;

#[NodeTypeDeclaration]
#[NodeTypeUiConfiguration(
    label: 'i18n',
    icon: 'icon-paper-plane',
    group: 'form.elements',
    position: 300,
)]
#[Flow\Proxy(false)]
readonly class Email implements
    FormField,
    LabelProvider,
    PlaceholderProvider,
    RequiredValidationProvider,
    CustomErrorMessageProvider
{
    use FormFieldProperties;
    use RequiredValidationProperties;
    use CustomErrorMessageProperties;

    public function __construct(
        public string $label,
        public ?string $placeholder,
        public bool $isMultiple = false,
    ) {
    }
}
