<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\AltchaFormField;

use Neos\Flow\Annotations as Flow;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeConstraintsDeclaration;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\SelectBoxEditor\SelectBoxEditorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\InspectorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\PropertyUiConfiguration;
use Sitegeist\PaperTiger\CPX\Components\Field\AltchaField\AltchaAutoMode;
use Sitegeist\PaperTiger\CPX\Components\Field\AltchaField\AltchaDisplayMode;
use Sitegeist\PaperTiger\CPX\Components\Field\AltchaField\AltchaInteractionType;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaDefinition;
use Sitegeist\PaperTiger\CPX\Domain\Validation\Validator\AltchaValidator;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldConstraint;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormFieldProperties;

#[NodeTypeDeclaration(
    new NodeTypeConstraintsDeclaration(fqns: [
        FieldConstraint::class => true,
    ]),
)]
#[NodeTypeUiConfiguration(
    label: 'i18n',
    icon: 'wrench',
    group: 'form.special',
    position: 10,
)]
#[Flow\Proxy(false)]
readonly class AltchaFormField implements FormField
{
    use FormFieldProperties;

    public function __construct(
        #[PropertyUiConfiguration(label: 'i18n', reloadIfChanged: true)]
        #[InspectorConfiguration(group: 'form', position: 20)]
        #[SelectBoxEditorConfiguration(
            values: [
                'standard' => [
                    'label' => 'Sitegeist.PaperTiger.CPX:NodeTypes.Field.AltchaFormField:properties.display.selectBoxEditor.values.standard',
                ],
                'invisible' => [
                    'label' => 'Sitegeist.PaperTiger.CPX:NodeTypes.Field.AltchaFormField:properties.display.selectBoxEditor.values.invisible',
                ],
            ],
            allowEmpty: false,
            minimumResultsForSearch: -1,
        )]
        public AltchaDisplayMode $display = AltchaDisplayMode::STANDARD,
        #[PropertyUiConfiguration(label: 'i18n', reloadIfChanged: true)]
        #[InspectorConfiguration(group: 'form', position: 30)]
        #[SelectBoxEditorConfiguration(
            values: [
                'off' => [
                    'label' => 'Sitegeist.PaperTiger.CPX:NodeTypes.Field.AltchaFormField:properties.auto.selectBoxEditor.values.off',
                ],
                'onfocus' => [
                    'label' => 'Sitegeist.PaperTiger.CPX:NodeTypes.Field.AltchaFormField:properties.auto.selectBoxEditor.values.onfocus',
                ],
                'onload' => [
                    'label' => 'Sitegeist.PaperTiger.CPX:NodeTypes.Field.AltchaFormField:properties.auto.selectBoxEditor.values.onload',
                ],
                'onsubmit' => [
                    'label' => 'Sitegeist.PaperTiger.CPX:NodeTypes.Field.AltchaFormField:properties.auto.selectBoxEditor.values.onsubmit',
                ],
            ],
            allowEmpty: false,
            minimumResultsForSearch: -1,
        )]
        public AltchaAutoMode $auto = AltchaAutoMode::ON_FOCUS,
        #[PropertyUiConfiguration(label: 'i18n', reloadIfChanged: true)]
        #[InspectorConfiguration(group: 'form', position: 40)]
        #[SelectBoxEditorConfiguration(
            values: [
                'native' => [
                    'label' => 'Sitegeist.PaperTiger.CPX:NodeTypes.Field.AltchaFormField:properties.type.selectBoxEditor.values.native',
                ],
                'checkbox' => [
                    'label' => 'Sitegeist.PaperTiger.CPX:NodeTypes.Field.AltchaFormField:properties.type.selectBoxEditor.values.checkbox',
                ],
                'switch' => [
                    'label' => 'Sitegeist.PaperTiger.CPX:NodeTypes.Field.AltchaFormField:properties.type.selectBoxEditor.values.switch',
                ],
            ],
            allowEmpty: false,
            minimumResultsForSearch: -1,
        )]
        public AltchaInteractionType $type = AltchaInteractionType::CHECKBOX,
        #[PropertyUiConfiguration(label: 'i18n', reloadIfChanged: true)]
        #[InspectorConfiguration(group: 'form', position: 50)]
        public bool $hideFooter = false,
        #[PropertyUiConfiguration(label: 'i18n', reloadIfChanged: true)]
        #[InspectorConfiguration(group: 'form', position: 60)]
        public bool $hideLogo = false,
    ) {
    }

    public function getSchemaTargetType(): string
    {
        return 'string';
    }

    public function requiresArrayOfSchema(): bool
    {
        return false;
    }

    public function applyToSchema(SchemaDefinition $schema): SchemaDefinition
    {
        return $schema->isRequiredWithId('altcha')
            ->validatorWithId('altcha', AltchaValidator::class);
    }
}
