<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Text\MultiLine;

use Neos\Flow\Annotations as Flow;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\SelectBoxEditor\SelectBoxEditorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\InspectorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\PropertyUiConfiguration;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormFieldProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\PlaceholderProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\LabelProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\StringLengthValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\StringLengthValidationProvider;

#[NodeTypeDeclaration]
#[NodeTypeUiConfiguration(
    label: 'i18n',
    icon: 'icon-pencil-square-o',
    group: 'form.elements',
    position: 200,
)]
#[Flow\Proxy(false)]
readonly class MultiLine implements
    FormField,
    LabelProvider,
    PlaceholderProvider,
    RequiredValidationProvider,
    StringLengthValidationProvider,
    CustomErrorMessageProvider
{
    use FormFieldProperties;
    use RequiredValidationProperties;
    use StringLengthValidationProperties;
    use CustomErrorMessageProperties;

    public function __construct(
        public string $label,
        public ?string $placeholder,
        #[PropertyUiConfiguration(label: 'i18n', reloadIfChanged: true)]
        #[InspectorConfiguration(group: 'form')]
        #[SelectBoxEditorConfiguration(
            values: [
                '2' => [
                    'label' => '2',
                ],
                '3' => [
                    'label' => '3',
                ],
                '4' => [
                    'label' => '4',
                ],
                '5' => [
                    'label' => '5',
                ],
                '8' => [
                    'label' => '8',
                ],
                '10' => [
                    'label' => '10',
                ],
                '15' => [
                    'label' => '15',
                ],
                '20' => [
                    'label' => '20',
                ],
            ],
            allowEmpty: true,
        )]
        public ?int $lineNumber,
    ) {
    }
}
