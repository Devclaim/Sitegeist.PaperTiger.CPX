<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\AltchaFormField;

use Neos\Flow\Annotations as Flow;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeConstraintsDeclaration;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
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
    label: 'Altcha (Captcha)',
    icon: 'wrench',
    group: 'form.special',
    position: 10,
)]
#[Flow\Proxy(false)]
readonly class AltchaFormField implements FormField
{
    use FormFieldProperties;

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
