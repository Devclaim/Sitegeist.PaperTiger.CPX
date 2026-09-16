<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain;

use Neos\ContentRepository\Core\SharedModel\Node\NodeAddress;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Neos\Domain\Model\RenderingMode;
use Neos\Neos\NodeTypes\Document;
use Neos\Neos\NodeTypes\Site;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Neos\Neos\Domain\NodeMapping\NodeMapperInterface;
use PackageFactory\Neos\ComponentView\FrontendNeosContextProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Form\Form;

final class FormSubmissionContextResolver
{
    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
        private readonly NodeMapperInterface $nodeMapper,
    ) {
    }

    /**
     * @param array<string,mixed> $arguments
     * @return NeosContext<Form,Document,Site>|null
     */
    public function resolveFormContext(ActionRequest $request, array $arguments): ?NeosContext
    {
        return $this->resolveContext($request, $arguments, 'paperTigerDocument', 'paperTigerNode');
    }


    /**
     * @param array<string,mixed> $arguments
     * @return NeosContext<Form,Document,Site>|null
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
            /** @var NeosContext<Document,Document,Site> $context */
            $context = $contextProvider->provideContext();
        } catch (\Throwable) {
            return null;
        }

        if ($targetFieldName === null) {
            return null;
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
        $form = $this->nodeMapper->map($targetNode, $context->subgraph);
        if (!$form instanceof Form) {
            return null;
        }

        return $context->with(
            current: $form
        );
    }
}
