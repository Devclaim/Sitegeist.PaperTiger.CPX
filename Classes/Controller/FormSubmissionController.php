<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Controller;

use Sitegeist\PaperTiger\CPX\Dto\FormSubmissionResponse;
use Sitegeist\SchemeOnYou\Application\OpenApiController;

final class FormSubmissionController extends OpenApiController
{
    public function formDataAction(): FormSubmissionResponse
    {
        \Neos\Flow\var_dump([
            'arguments' => $this->request->getArguments()
        ], 'PaperTiger form submission');
        exit;

        return new FormSubmissionResponse('');
    }

    public function jsonAction(): FormSubmissionResponse
    {
        return new FormSubmissionResponse('');
    }
}
