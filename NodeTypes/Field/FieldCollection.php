<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field;

use Neos\Flow\Annotations as Flow;
use Neos\Neos\NodeTypes\Content;
use Neos\Neos\NodeTypes\ContentCollection;
use PackageFactory\OPGM\Domain\NodeType\ChildRelationDeclaration;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeConstraintsDeclaration;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;

#[NodeTypeDeclaration(
    constraints: new NodeTypeConstraintsDeclaration([
        Content::class => false,
        FieldConstraint::class => true,
    ])
)]
#[Flow\Proxy(false)]
readonly class FieldCollection extends ContentCollection
{
    public function __construct(
        #[ChildRelationDeclaration]
        public FormFields $fields,
    ) {
    }

    /**
     * @return FormField[]
     */
    public function findFieldsRecursively(): array
    {
        $result = [];
        foreach ($this->fields as $field) {
            if ($field instanceof FormField) {
                $result[] = $field;
            }
            if ($field instanceof FieldCollection) {
                $result = array_merge($result, $field->findFieldsRecursively());
            }
        }

        return $result;
    }
}
