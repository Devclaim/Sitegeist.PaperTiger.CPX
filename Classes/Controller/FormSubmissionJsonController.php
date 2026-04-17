<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Controller;

use Neos\Flow\Mvc\ActionResponse;
use Sitegeist\PaperTiger\CPX\Domain\Action\MessageAction;
use Sitegeist\PaperTiger\CPX\Domain\FormSubmissionActionExecutor;
use Sitegeist\PaperTiger\CPX\Domain\FormSubmissionValidator;
use Sitegeist\PaperTiger\CPX\Dto\FormSubmissionJsonErrorResponse;
use Sitegeist\PaperTiger\CPX\Dto\FormSubmissionJsonResponse;
use Sitegeist\PaperTiger\CPX\Dto\FormSubmissionRequest;
use Sitegeist\SchemeOnYou\Application\OpenApiController;
use Sitegeist\SchemeOnYou\Domain\Metadata\RequestBody;
use Sitegeist\SchemeOnYou\Domain\Path\RequestBodyContentType;

final class FormSubmissionJsonController extends OpenApiController
{
    public function __construct(
        private readonly FormSubmissionValidator $formSubmissionValidator,
        private readonly FormSubmissionActionExecutor $formSubmissionActionExecutor,
    ) {
    }

    public function jsonAction(
        #[RequestBody(contentType: RequestBodyContentType::CONTENT_TYPE_JSON)]
        FormSubmissionRequest $formSubmissionRequest,
    ): FormSubmissionJsonResponse|FormSubmissionJsonErrorResponse {
        $arguments = [];
        foreach ($formSubmissionRequest->arguments->items as $argument) {
            $arguments[$argument->name] = $argument->value;
        }

        $validationResult = $this->formSubmissionValidator->validate($this->request, $arguments);

        if ($validationResult->hasErrors()) {
            return new FormSubmissionJsonErrorResponse(
                success: false,
                errors: $validationResult->errors,
            );
        }

        $actionResponse = $this->formSubmissionActionExecutor->execute($this->request, $arguments);
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
}
