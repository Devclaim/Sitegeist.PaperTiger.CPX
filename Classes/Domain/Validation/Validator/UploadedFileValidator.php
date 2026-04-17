<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Validator;

use Neos\Flow\Validation\Validator\AbstractValidator;
use Psr\Http\Message\UploadedFileInterface;

final class UploadedFileValidator extends AbstractValidator
{
    /**
     * @var array<string, array{0: mixed, 1: string, 2: string, 3: bool}>
     */
    protected $supportedOptions = [
        'allowedExtensions' => [[], 'Array of allowed file extensions', 'array', false],
        'maximumSize' => [null, 'Maximum file size in bytes', 'integer', false],
    ];

    protected function isValid($uploadedFile): void
    {
        if ($uploadedFile === null || $uploadedFile === '') {
            return;
        }

        if (!$uploadedFile instanceof UploadedFileInterface) {
            $this->addError('The given value was not an UploadedFileInterface instance.', 1744721002);
            return;
        }

        if ($uploadedFile->getError() === UPLOAD_ERR_NO_FILE) {
            return;
        }

        $allowedExtensions = array_map(
            static fn (mixed $extension): string => strtolower((string)$extension),
            (array)$this->options['allowedExtensions']
        );

        if ($allowedExtensions !== []) {
            $clientFilename = $uploadedFile->getClientFilename() ?? '';
            $extension = strtolower((string)pathinfo($clientFilename, PATHINFO_EXTENSION));

            if (!in_array($extension, $allowedExtensions, true)) {
                $this->addError(
                    'The file extension has to be one of "%s", "%s" is not allowed.',
                    1744721003,
                    [implode(', ', $allowedExtensions), $extension]
                );
            }
        }

        $maximumSize = $this->options['maximumSize'];
        if (is_int($maximumSize) && $uploadedFile->getSize() !== null && $uploadedFile->getSize() > $maximumSize) {
            $this->addError(
                'The file size exceeds the maximum allowed size of %s bytes.',
                1744721004,
                [$maximumSize]
            );
        }
    }
}