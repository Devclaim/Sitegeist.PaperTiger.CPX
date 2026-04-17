<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Uri;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\ContentRepository\Core\SharedModel\Node\NodeAddress;
use Neos\ContentRepository\Core\SharedModel\Node\NodeAggregateId;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Exception\NoMatchingRouteException;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Repository\AssetRepository;
use Neos\Neos\FrontendRouting\NodeUriBuilderFactory;
use Neos\Neos\FrontendRouting\Options;
use Psr\Log\LoggerInterface;

/**
 * PHP-side variant of `Neos.Neos:ConvertUris` for PaperTiger actions.
 *
 * We only need this for action execution (redirects, emails, ...), so we keep it
 * independent from Fusion runtime and only copy the essential conversion logic.
 */
final class ConvertUrisService
{
    public function __construct(
        private readonly AssetRepository $assetRepository,
        private readonly ResourceManager $resourceManager,
        private readonly LoggerInterface $systemLogger,
        private readonly NodeUriBuilderFactory $nodeUriBuilderFactory,
    ) {
    }

    public function convertUriString(string $uri, Node $contextNode, ?ActionRequest $request = null, bool $absolute = false): string
    {
        if ($uri === '' || (!str_starts_with($uri, 'node://') && !str_starts_with($uri, 'asset://'))) {
            return $uri;
        }

        if (str_starts_with($uri, 'node://')) {
            return $this->convertNodeUri($uri, $contextNode, $request, $absolute) ?? $uri;
        }

        return $this->convertAssetUri($uri) ?? $uri;
    }

    private function convertNodeUri(string $uri, Node $contextNode, ?ActionRequest $request, bool $absolute): ?string
    {
        $aggregateId = substr($uri, strlen('node://'));
        if ($aggregateId === '') {
            return null;
        }

        try {
            $nodeAddress = NodeAddress::fromNode($contextNode)->withAggregateId(NodeAggregateId::fromString($aggregateId));
            $options = $absolute ? Options::createForceAbsolute() : Options::createEmpty();

            $nodeUriBuilder = $request instanceof ActionRequest
                ? $this->nodeUriBuilderFactory->forActionRequest($request)
                : $this->nodeUriBuilderFactory->forActionRequest(ActionRequest::fromHttpRequest(\GuzzleHttp\Psr7\ServerRequest::fromGlobals()));

            $format = $request?->getFormat();
            if (is_string($format) && $format !== '' && $format !== 'html') {
                $options = $options->withCustomFormat($format);
            }

            return (string)$nodeUriBuilder->uriFor($nodeAddress, $options);
        } catch (NoMatchingRouteException $e) {
            $this->systemLogger->warning(sprintf('Could not resolve "%s" to a node uri: %s', $uri, $e->getMessage()));
            return null;
        } catch (\Throwable $e) {
            $this->systemLogger->warning(sprintf('Could not resolve "%s" to a node uri: %s', $uri, $e->getMessage()));
            return null;
        }
    }

    private function convertAssetUri(string $uri): ?string
    {
        $identifier = substr($uri, strlen('asset://'));
        if ($identifier === '') {
            return null;
        }

        $asset = $this->assetRepository->findByIdentifier($identifier);
        if (!$asset instanceof AssetInterface) {
            return null;
        }

        return $this->resourceManager->getPublicPersistentResourceUri($asset->getResource()) ?: null;
    }
}
