<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Validator;

use Neos\Flow\Validation\Validator\AbstractValidator;
use Neos\Flow\Validation\ValidatorResolver;
use Psr\Http\Message\UploadedFileInterface;

final class UploadedFileCollectionValidator extends AbstractValidator
{
    /**
     * @var array<string, array{0: mixed, 1: string, 2: string, 3: bool}>
     */
    protected $supportedOptions = [
        'allowedExtensions' => [[], 'Array of allowed file extensions', 'array', false],
        'maximumSize' => [null, 'Maximum file size in bytes', 'integer', false],
    ];

    public function __construct(
        ValidatorResolver $validatorResolver,
        array $options = [],
    ) {
        parent::__construct($options);
        $this->validatorResolver = $validatorResolver;
    }

    private ValidatorResolver $validatorResolver;

    protected function isValid($value): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (!is_array($value)) {
            $this->addError('The given value was not an array.', 1744721005);
            return;
        }

        $validator = $this->validatorResolver->createValidator(UploadedFileValidator::class, [
            'allowedExtensions' => (array)$this->options['allowedExtensions'],
            'maximumSize' => $this->options['maximumSize'],
        ]);

        if ($validator === null) {
            throw new \RuntimeException('Validator could not get created.', 1744721006);
        }

        foreach ($value as $index => $item) {
            if (!$item instanceof UploadedFileInterface) {
                $this->addError(sprintf('The item at index %s was not an UploadedFileInterface instance.', (string)$index), 1744721007);
                continue;
            }

            $result = $validator->validate($item);
            if ($result->hasErrors()) {
                foreach ($result->getErrors() as $error) {
                    $this->result->forProperty((string)$index)->addError($error);
                }
            }
        }
    }
}