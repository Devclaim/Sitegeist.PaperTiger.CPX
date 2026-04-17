<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaInterface;

final class FieldSchemaProviderResolver
{
    /**
     * @param array<string, string> $providerClassNamesByNodeType
     */
    public function __construct(
        #[Flow\InjectConfiguration(path: 'validation.schemaProviders', package: 'Sitegeist.PaperTiger.CPX')]
        array $providerClassNamesByNodeType,
        private readonly ObjectManagerInterface $objectManager,
    ) {
        $this->providerClassNamesByNodeType = $providerClassNamesByNodeType;
    }

    /**
     * @var array<string, string>
     */
    private readonly array $providerClassNamesByNodeType;

    public function resolve(NeosContext $context, Node $fieldNode): ?SchemaInterface
    {
        $nodeType = $context->nodes->tryGetNodeType($fieldNode);
        if ($nodeType === null) {
            return null;
        }

        foreach ($this->providerClassNamesByNodeType as $configuredNodeType => $providerClassName) {
            if ($nodeType->isOfType($configuredNodeType)) {
                /** @var FieldSchemaProviderInterface $provider */
                $provider = $this->objectManager->get($providerClassName);
                return $provider->build($context, $fieldNode);
            }
        }

        return null;
    }
}