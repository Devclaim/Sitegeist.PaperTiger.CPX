<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaInterface;
use Sitegeist\PaperTiger\CPX\Domain\Validation\Validator\AltchaValidator;

final class AltchaFieldSchemaProvider extends AbstractFieldSchemaProvider
{
    public function build(NeosContext $context, Node $fieldNode): ?SchemaInterface
    {
        $schema = $this->createSchema('string');
        $schema->isRequiredWithId('altcha');
        $schema->validatorWithId('altcha', AltchaValidator::class);

        return $schema;
    }
}
