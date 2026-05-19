<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain;

use Neos\ContentRepository\Core\SharedModel\Node\NodeAggregateId;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\ActionResponse;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Psr\Http\Message\UploadedFileInterface;
use Sitegeist\PaperTiger\CPX\Domain\Action\ConfigurableActionInterface;
use Sitegeist\PaperTiger\CPX\Domain\Action\Specification\EmailActionSpecification;
use Sitegeist\PaperTiger\CPX\Domain\Action\EmailAction;
use Sitegeist\PaperTiger\CPX\Domain\Action\MessageAction;
use Sitegeist\PaperTiger\CPX\Domain\Action\RedirectAction;
use Sitegeist\PaperTiger\CPX\Components\Form\ActionType;
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

        $actionType = $this->readActionType($context);
        if ($actionType === ActionType::MESSAGE) {
            $message = $this->replaceTokens(
                $context->nodes->getStringValue($context->node, 'message'),
                $arguments
            );
            $this->messageAction->perform($request, $message);
        }

        foreach ($this->readEmailActionSpecifications($context) as $emailAction) {
            $actionResponse = $this->performAction(
                EmailAction::class,
                $this->buildEmailActionOptionsFromSpecification($emailAction, $arguments),
            );
            if ($actionResponse instanceof ActionResponse) {
                $response = $actionResponse;
            }
        }

        $redirectUri = $this->readRedirectUri($context);
        if ($actionType === ActionType::REDIRECT && is_string($redirectUri) && $redirectUri !== '') {
            $actionResponse = $this->performAction(
                RedirectAction::class,
                $this->buildRedirectActionOptions($context, $redirectUri, $arguments),
            );
            if ($actionResponse instanceof ActionResponse) {
                $response = $actionResponse;
            }
        }

        return $response;
    }

    /**
     * @return array<int, EmailActionSpecification>
     */
    private function readEmailActionSpecifications(NeosContext $context): array
    {
        $value = $context->node->getProperty('emailAction');
        if (!is_array($value)) {
            return [];
        }

        return array_values(array_filter(array_map(
            static fn (mixed $entry): ?EmailActionSpecification => is_array($entry)
                ? EmailActionSpecification::fromArray($entry)
                : null,
            $value,
        )));
    }

    private function readRedirectUri(NeosContext $context): ?string
    {
        return $context->nodes->getStringValue($context->node, 'redirectAction');
    }

    private function readActionType(NeosContext $context): ActionType
    {
        $actionType = $context->nodes->getStringValue($context->node, 'actionType');
        return ActionType::tryFrom((string)$actionType) ?? ActionType::MESSAGE;
    }

    /**
     * @param array<string,mixed> $arguments
     * @return array<string,mixed>
     */
    private function buildRedirectActionOptions(
        NeosContext $context,
        string $redirectUri,
        array $arguments,
    ): array
    {
        return [
            'uri' => $this->resolveRedirectUri($context, $arguments, $redirectUri),
        ];
    }

    /**
     * @param array<string,mixed> $arguments
     * @return array<string,mixed>
     */
    private function buildEmailActionOptionsFromSpecification(
        EmailActionSpecification $action,
        array $arguments,
    ): array
    {
        $plaintext = $this->replaceTokens($action->plaintext, $arguments);
        $html = $this->replaceTokens($action->html, $arguments);

        if ($action->format === 'html') {
            $plaintext = null;
        }
        if ($action->format === 'plaintext') {
            $html = null;
        }

        return [
            'subject' => $this->replaceTokens($action->subject, $arguments),
            'text' => $plaintext,
            'html' => $html,
            'recipientAddress' => $this->replaceTokens($action->recipientAddress, $arguments),
            'recipientName' => $this->replaceTokens($action->recipientName, $arguments),
            'senderAddress' => $this->replaceTokens($action->senderAddress, $arguments),
            'senderName' => $this->replaceTokens($action->senderName, $arguments),
            'replyToAddress' => $this->replaceTokens($action->replyToAddress, $arguments),
            'carbonCopyAddress' => $this->replaceTokens($action->carbonCopyAddress, $arguments),
            'blindCarbonCopyAddress' => $this->replaceTokens($action->blindCarbonCopyAddress, $arguments),
            'attachments' => $action->attachUploads
                ? $this->collectUploadArguments($arguments)
                : null,
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

        array_walk_recursive(
            $arguments,
            static function (mixed $leaf) use (&$uploads): void {
                if ($leaf instanceof UploadedFileInterface) {
                    $uploads[] = $leaf;
                }
            }
        );

        return $uploads;
    }

    private function resolveRedirectUri(NeosContext $context, array $arguments, ?string $uriValue): ?string
    {
        $uri = $this->replaceTokens($uriValue, $arguments);
        if (!is_string($uri) || $uri === '') {
            return null;
        }

        return $this->convertUrisService->convertUriString($uri, $context->documentNode, $context->request, false, true);
    }
}
