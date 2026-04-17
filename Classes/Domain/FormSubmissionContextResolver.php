<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain;

use Neos\ContentRepository\Core\SharedModel\Node\NodeAddress;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Neos\Domain\Model\RenderingMode;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\Neos\ComponentView\FrontendNeosContextProvider;

final class FormSubmissionContextResolver
{
    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
    ) {
    }

    /**
     * @param array<string,mixed> $arguments
     */
    public function resolveFormContext(ActionRequest $request, array $arguments): ?NeosContext
    {
        return $this->resolveContext($request, $arguments, 'paperTigerDocument', 'paperTigerNode');
    }

    /**
     * @param array<string,mixed> $arguments
     */
    public function resolveDocumentContext(ActionRequest $request, array $arguments): ?NeosContext
    {
        return $this->resolveContext($request, $arguments, 'paperTigerDocument');
    }

    /**
     * @param array<string,mixed> $arguments
     */
    private function resolveContext(ActionRequest $request, array $arguments, string $documentFieldName, ?string $targetFieldName = null): ?NeosContext
    {
        $serializedDocumentNodeAddress = $arguments[$documentFieldName] ?? null;
        if (!is_string($serializedDocumentNodeAddress) || $serializedDocumentNodeAddress === '') {
            return null;
        }

        try {
            $documentNodeAddress = NodeAddress::fromJsonString($serializedDocumentNodeAddress);
        } catch (\InvalidArgumentException) {
            return null;
        }

        /** @var FrontendNeosContextProvider $contextProvider */
        $contextProvider = $this->objectManager->get(
            FrontendNeosContextProvider::class,
            $documentNodeAddress,
            $request,
            RenderingMode::createFrontend(),
        );

        try {
            $context = $contextProvider->provideContext();
        } catch (\Throwable) {
            return null;
        }

        if ($targetFieldName === null) {
            return $context;
        }

        $serializedTargetNodeAddress = $arguments[$targetFieldName] ?? null;
        if (!is_string($serializedTargetNodeAddress) || $serializedTargetNodeAddress === '') {
            return null;
        }

        try {
            $targetNodeAddress = NodeAddress::fromJsonString($serializedTargetNodeAddress);
        } catch (\InvalidArgumentException) {
            return null;
        }

        $targetNode = $context->subgraph->findNodeById($targetNodeAddress->aggregateId);
        if ($targetNode === null) {
            return null;
        }

        return $context->with(node: $targetNode);
    }
}
