<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\Flow\Validation\Validator\EmailAddressValidator;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaInterface;

final class EmailFieldSchemaProvider extends AbstractFieldSchemaProvider
{
    public function build(NeosContext $context, Node $fieldNode): ?SchemaInterface
    {
        $schema = $this->createSchema('string');
        $this->applyRequiredValidation($context, $fieldNode, $schema);
        $schema->validator(EmailAddressValidator::class);

        return $schema;
    }
}
