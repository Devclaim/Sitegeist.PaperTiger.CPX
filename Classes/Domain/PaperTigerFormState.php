<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\ActionRequest;
use Sitegeist\PaperTiger\CPX\Dto\FormSubmissionValidationError;
use Sitegeist\PaperTiger\CPX\Dto\FormSubmissionValidationErrorCollection;

#[Flow\Proxy(false)]
final readonly class PaperTigerFormState
{
    /**
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        public array $arguments,
        public FormSubmissionValidationErrorCollection $errors,
    ) {
    }

    public static function fromRequest(ActionRequest $request): ?self
    {
        $formState = $request->getInternalArgument('__paperTigerFormState');

        return $formState instanceof self ? $formState : null;
    }

    public function hasErrorsFor(string $fieldName): bool
    {
        return $this->getErrorsFor($fieldName) !== [];
    }

    public function getGeneralError(): ?FormSubmissionValidationError
    {
        return $this->getFirstError('__general');
    }

    /**
     * @return array<int, FormSubmissionValidationError>
     */
    public function getGeneralErrors(): array
    {
        return $this->getErrorsFor('__general');
    }

    /**
     * @return array<int, FormSubmissionValidationError>
     */
    public function getErrorsFor(string $fieldName): array
    {
        $result = [];
        foreach ($this->errors->items as $error) {
            if ($error->fieldName === $fieldName) {
                $result[] = $error;
            }
        }

        return $result;
    }

    public function getFirstError(string $fieldName): ?FormSubmissionValidationError
    {
        foreach ($this->errors->items as $error) {
            if ($error->fieldName === $fieldName) {
                return $error;
            }
        }

        return null;
    }

    public function getValue(string $fieldName): mixed
    {
        return $this->arguments[$fieldName] ?? null;
    }

    public function getStringValue(string $fieldName): ?string
    {
        $value = $this->getValue($fieldName);

        return is_scalar($value) ? (string)$value : null;
    }

    /**
     * @return array<int, string>
     */
    public function getStringValues(string $fieldName): array
    {
        $value = $this->getValue($fieldName);

        if (is_iterable($value)) {
            return array_values(
                array_filter(
                    array_map(
                        static fn (mixed $item): ?string => is_scalar($item) ? (string)$item : null,
                        is_array($value) ? $value : iterator_to_array($value),
                    ),
                    static fn (?string $item): bool => $item !== null,
                ),
            );
        }

        if (is_scalar($value)) {
            return [(string)$value];
        }

        return [];
    }
}
