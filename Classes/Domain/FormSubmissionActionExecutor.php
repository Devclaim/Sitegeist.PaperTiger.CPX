<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain;

use Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindChildNodesFilter;
use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\ContentRepository\Core\SharedModel\Node\NodeAddress;
use Neos\ContentRepository\Core\SharedModel\Node\NodeAggregateId;
use Neos\ContentRepository\Core\SharedModel\Node\NodeName;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\ActionResponse;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Psr\Http\Message\UploadedFileInterface;
use Sitegeist\PaperTiger\CPX\Domain\Action\ConfigurableActionInterface;
use Sitegeist\PaperTiger\CPX\Domain\Action\EmailAction;
use Sitegeist\PaperTiger\CPX\Domain\Action\MessageAction;
use Sitegeist\PaperTiger\CPX\Domain\Action\RedirectAction;
use Sitegeist\PaperTiger\CPX\Domain\Uri\ConvertUrisService;

final class FormSubmissionActionExecutor
{
    public function __construct(
        private readonly FormSubmissionContextResolver $formSubmissionContextResolver,
        private readonly ObjectManagerInterface $objectManager,
        private readonly ConvertUrisService $convertUrisService,
        private readonly MessageAction $messageAction,
    ) {
    }

    /**
     * @param array<string,mixed> $arguments
     */
    public function execute(ActionRequest $request, array $arguments): ?ActionResponse
    {
        $context = $this->formSubmissionContextResolver->resolveFormContext($request, $arguments);
        if (!$context instanceof NeosContext) {
            return null;
        }

        $response = null;

        foreach ($this->resolveActionNodes($context) as $actionNode) {
            $actionResponse = $this->executeActionNode($request, $context, $actionNode, $arguments);
            if ($actionResponse instanceof ActionResponse) {
                $response = $actionResponse;
            }
        }

        return $response;
    }

    /**
     * @return array<int,Node>
     */
    private function resolveActionNodes(NeosContext $context): array
    {
        $actionsCollectionNode = $context->subgraph->findNodeByPath(NodeName::fromString('actions'), $context->node->aggregateId);
        if (!$actionsCollectionNode instanceof Node) {
            return [];
        }

        return iterator_to_array(
            $context->subgraph->findChildNodes($actionsCollectionNode->aggregateId, FindChildNodesFilter::create())
        );
    }

    /**
     * @param array<string,mixed> $arguments
     */
    private function executeActionNode(ActionRequest $request, NeosContext $context, Node $actionNode, array $arguments): ?ActionResponse
    {
        $nodeType = $context->nodes->tryGetNodeType($actionNode);
        if ($nodeType === null) {
            return null;
        }

        if ($nodeType->isOfType('Sitegeist.PaperTiger.CPX:Action.Message')) {
            $message = $this->replaceTokens(
                $context->nodes->getStringValue($actionNode, 'message'),
                $arguments,
            );
            $this->messageAction->perform($request, $message);

            return null;
        }

        $actionClassName = $this->resolveActionClassName($actionNode, $context);
        if ($actionClassName === null) {
            return null;
        }

        return $this->performAction(
            $actionClassName,
            $this->buildActionOptions($context, $actionNode, $arguments, $actionClassName),
        );
    }

    /**
     * @return class-string<ConfigurableActionInterface>|null
     */
    private function resolveActionClassName(Node $actionNode, NeosContext $context): ?string
    {
        $nodeType = $context->nodes->tryGetNodeType($actionNode);
        if ($nodeType === null) {
            return null;
        }

        return match (true) {
            $nodeType->isOfType('Sitegeist.PaperTiger.CPX:Action.Redirect') => RedirectAction::class,
            $nodeType->isOfType('Sitegeist.PaperTiger.CPX:Action.Email') => EmailAction::class,
            default => null,
        };
    }

    /**
     * @param class-string<ConfigurableActionInterface> $actionClassName
     * @param array<string,mixed> $arguments
     * @return array<string,mixed>
     */
    private function buildActionOptions(NeosContext $context, Node $actionNode, array $arguments, string $actionClassName): array
    {
        return match ($actionClassName) {
            RedirectAction::class => $this->buildRedirectActionOptions($context, $actionNode, $arguments),
            EmailAction::class => $this->buildEmailActionOptions($context, $actionNode, $arguments),
            default => [],
        };
    }

    /**
     * @param array<string,mixed> $arguments
     * @return array<string,mixed>
     */
    private function buildRedirectActionOptions(NeosContext $context, Node $actionNode, array $arguments): array
    {
        return [
            'uri' => $this->resolveRedirectUri($context, $arguments, $actionNode),
        ];
    }

    /**
     * @param array<string,mixed> $arguments
     * @return array<string,mixed>
     */
    private function buildEmailActionOptions(NeosContext $context, Node $actionNode, array $arguments): array
    {
        $format = $context->nodes->getStringValue($actionNode, 'format') ?? 'plaintext';
        $plaintext = $this->replaceTokens($context->nodes->getStringValue($actionNode, 'plaintext'), $arguments);
        $html = $this->replaceTokens($context->nodes->getStringValue($actionNode, 'html'), $arguments);

        if ($format === 'html') {
            $plaintext = null;
        }
        if ($format === 'plaintext') {
            $html = null;
        }

        return [
            'subject' => $this->replaceTokens($context->nodes->getStringValue($actionNode, 'subject'), $arguments),
            'text' => $plaintext,
            'html' => $html,
            'recipientAddress' => $this->replaceTokens($context->nodes->getStringValue($actionNode, 'recipientAddress'), $arguments),
            'recipientName' => $this->replaceTokens($context->nodes->getStringValue($actionNode, 'recipientName'), $arguments),
            'senderAddress' => $this->replaceTokens($context->nodes->getStringValue($actionNode, 'senderAddress'), $arguments),
            'senderName' => $this->replaceTokens($context->nodes->getStringValue($actionNode, 'senderName'), $arguments),
            'replyToAddress' => $this->replaceTokens($context->nodes->getStringValue($actionNode, 'replyToAddress'), $arguments),
            'carbonCopyAddress' => $this->replaceTokens($context->nodes->getStringValue($actionNode, 'carbonCopyAddress'), $arguments),
            'blindCarbonCopyAddress' => $this->replaceTokens($context->nodes->getStringValue($actionNode, 'blindCarbonCopyAddress'), $arguments),
            'attachments' => ($context->nodes->getBoolValue($actionNode, 'attachUploads') ?? false)
                ? $this->collectUploadArguments($arguments)
                : null,
            'testMode' => $context->nodes->getBoolValue($actionNode, 'testMode') ?? false,
        ];
    }

    /**
     * @param class-string<ConfigurableActionInterface> $actionClassName
     * @param array<string,mixed> $options
     */
    private function performAction(string $actionClassName, array $options): ?ActionResponse
    {
        /** @var ConfigurableActionInterface $action */
        $action = $this->objectManager->get($actionClassName);

        return $action->withOptions(
            array_filter($options, static fn (mixed $value): bool => $value !== null)
        )->perform();
    }

    /**
     * @param array<string,mixed> $arguments
     */
    private function replaceTokens(?string $value, array $arguments): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        return (string)preg_replace_callback(
            '/\{([A-Za-z0-9_-]+)\}/',
            static function (array $matches) use ($arguments): string {
                $replacement = $arguments[$matches[1]] ?? '';

                if ($replacement instanceof UploadedFileInterface) {
                    return $replacement->getClientFilename() ?? '';
                }

                if (is_array($replacement)) {
                    return implode(', ', array_map(static fn (mixed $item): string => is_scalar($item) ? (string)$item : '', $replacement));
                }

                return is_scalar($replacement) ? (string)$replacement : '';
            },
            $value,
        );
    }

    /**
     * @param array<string,mixed> $arguments
     * @return array<int, UploadedFileInterface>
     */
    private function collectUploadArguments(array $arguments): array
    {
        $uploads = [];

        foreach ($arguments as $value) {
            if ($value instanceof UploadedFileInterface) {
                $uploads[] = $value;
                continue;
            }

            if (!is_array($value)) {
                continue;
            }

            foreach ($value as $item) {
                if ($item instanceof UploadedFileInterface) {
                    $uploads[] = $item;
                }
            }
        }

        return $uploads;
    }

    private function resolveRedirectUri(NeosContext $context, array $arguments, Node $actionNode): ?string
    {
        $uri = $this->replaceTokens($context->nodes->getStringValue($actionNode, 'uri'), $arguments);
        if (!is_string($uri) || $uri === '') {
            return null;
        }

        return $this->convertUrisService->convertUriString($uri, $context->documentNode, $context->request, false, true);
    }
}
