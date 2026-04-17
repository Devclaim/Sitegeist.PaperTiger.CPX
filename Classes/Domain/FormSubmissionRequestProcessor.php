<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain;

use Neos\ContentRepository\Core\SharedModel\Node\NodeAddress;
use PackageFactory\Neos\ComponentEngine\NeosContext;

final class FormSubmissionRequestProcessor
{
    public const REQUEST_ARGUMENT_SUCCESS = '__paperTigerSuccess';

    public function __construct(
        private readonly FormSubmissionActionExecutor $formSubmissionActionExecutor,
        private readonly FormSubmissionValidator $formSubmissionValidator,
    ) {
    }

    public function process(NeosContext $context): void
    {
        $request = $context->request;

        if ($request->getHttpRequest()->getMethod() !== 'POST') {
            return;
        }

        $arguments = $this->extractSubmittedArguments($context);
        if (!$this->isSubmissionForCurrentForm($context, $arguments)) {
            return;
        }

        $validationResult = $this->formSubmissionValidator->validate($request, $arguments);
        $request->setArgument(
            '__paperTigerFormState',
            new PaperTigerFormState($validationResult->arguments, $validationResult->errors),
        );

        if ($validationResult->hasErrors()) {
            return;
        }

        // Mark the submission as successful even if no actions are configured.
        $request->setArgument(self::REQUEST_ARGUMENT_SUCCESS, true);

        if ($request->getInternalArgument('__paperTigerActionResponse') !== null) {
            return;
        }

        $actionResponse = $this->formSubmissionActionExecutor->execute($request, $validationResult->arguments);
        if ($actionResponse !== null) {
            $request->setArgument('__paperTigerActionResponse', $actionResponse);
        }
    }

    /**
     * @param array<string, mixed> $arguments
     */
    private function isSubmissionForCurrentForm(NeosContext $context, array $arguments): bool
    {
        $submittedNodeAddress = $arguments['paperTigerNode'] ?? null;
        if (!is_string($submittedNodeAddress) || $submittedNodeAddress === '') {
            return false;
        }

        return $submittedNodeAddress === NodeAddress::fromNode($context->node)->toJson();
    }

    /**
     * @return array<string, mixed>
     */
    private function extractSubmittedArguments(NeosContext $context): array
    {
        $httpRequest = $context->request->getHttpRequest();
        $parsedBody = $httpRequest->getParsedBody();
        $bodyArguments = is_array($parsedBody) ? $parsedBody : [];

        return array_replace_recursive(
            $bodyArguments,
            $this->normalizeUploadedFiles($httpRequest->getUploadedFiles()),
        );
    }

    /**
     * @param array<string, mixed> $uploadedFiles
     * @return array<string, mixed>
     */
    private function normalizeUploadedFiles(array $uploadedFiles): array
    {
        $normalized = [];

        foreach ($uploadedFiles as $key => $value) {
            if (is_array($value)) {
                $normalized[$key] = $this->normalizeUploadedFiles($value);
                continue;
            }

            $normalized[$key] = $value;
        }

        return $normalized;
    }
}
