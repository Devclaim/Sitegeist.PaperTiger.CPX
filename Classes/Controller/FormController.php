<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Controller;

use Neos\Flow\Mvc\ActionResponse;
use Sitegeist\PaperTiger\CPX\Domain\Action\MessageAction;
use Sitegeist\PaperTiger\CPX\Domain\FormSubmissionActionExecutor;
use Sitegeist\PaperTiger\CPX\Domain\FormSubmissionValidator;
use Sitegeist\PaperTiger\CPX\Dto\FormSubmissionJsonErrorResponse;
use Sitegeist\PaperTiger\CPX\Dto\FormSubmissionJsonResponse;
use Sitegeist\SchemeOnYou\Application\OpenApiController;

final class FormController extends OpenApiController
{
    public function __construct(
        private readonly FormSubmissionValidator $formSubmissionValidator,
        private readonly FormSubmissionActionExecutor $formSubmissionActionExecutor,
    ) {
    }

    public function submitAction(): FormSubmissionJsonResponse|FormSubmissionJsonErrorResponse
    {
        $arguments = $this->extractSubmittedArguments();

        $validationResult = $this->formSubmissionValidator->validate($this->request, $arguments);
        if ($validationResult->hasErrors()) {
            return new FormSubmissionJsonErrorResponse(
                success: false,
                errors: $validationResult->errors,
            );
        }

        $actionResponse = $this->formSubmissionActionExecutor->execute($this->request, $validationResult->arguments);

        $message = $this->request->getInternalArgument(MessageAction::REQUEST_ARGUMENT_MESSAGE);
        $message = is_string($message) && $message !== '' ? $message : null;

        if (!$actionResponse instanceof ActionResponse) {
            return new FormSubmissionJsonResponse(
                success: true,
                errors: $validationResult->errors,
                message: $message,
            );
        }

        $redirectUri = $actionResponse->getRedirectUri();
        $content = $actionResponse->getContent();

        return new FormSubmissionJsonResponse(
            success: true,
            errors: $validationResult->errors,
            redirectUri: $redirectUri !== null ? (string)$redirectUri : null,
            content: $content !== '' ? $content : null,
            message: $message,
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function extractSubmittedArguments(): array
    {
        $httpRequest = $this->request->getHttpRequest();
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

