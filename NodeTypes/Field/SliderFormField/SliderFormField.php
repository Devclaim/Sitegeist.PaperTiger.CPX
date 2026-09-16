<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\SliderFormField;

use Neos\Flow\Annotations as Flow;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaDefinition;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormFieldProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Field\SliderConfigurationProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\LabelProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProperties;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProvider;

#[NodeTypeDeclaration]
#[NodeTypeUiConfiguration(
    label: 'i18n',
    icon: 'icon-sliders-h',
    group: 'form.elements',
    position: 1000,
)]
#[Flow\Proxy(false)]
readonly class SliderFormField implements
    FormField,
    LabelProvider,
    RequiredValidationProvider,
    CustomErrorMessageProvider,
    SliderConfigurationProvider
{
    use FormFieldProperties;
    use RequiredValidationProperties;
    use CustomErrorMessageProperties;

    public function __construct(
        public string $label,
        public int $minimumValue = 0,
        public int $maximumValue = 10,
        public int $stepValue = 1,
    ) {
    }

    public function getSchemaTargetType(): string
    {
        return 'integer';
    }

    public function requiresArrayOfSchema(): bool
    {
        return false;
    }

    public function applyToSchema(SchemaDefinition $schema): SchemaDefinition
    {
        return $schema;
    }
}
