<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain;

use Neos\Error\Messages\Error;
use Neos\Flow\Mvc\ActionRequest;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\FieldSchemaResolver;
use Sitegeist\PaperTiger\CPX\Domain\Validation\ValidationError;
use Sitegeist\PaperTiger\CPX\Dto\FormSubmissionValidationError;
use Sitegeist\PaperTiger\CPX\Dto\FormSubmissionValidationErrorCollection;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\CustomErrorMessageProvider;

final class FormSubmissionValidator
{
    public function __construct(
        private readonly FormSubmissionContextResolver $formSubmissionContextResolver,
        private readonly FieldSchemaResolver $fieldSchemaProviderResolver,
    ) {
    }

    /**
     * @param array<string, mixed> $arguments
     */
    public function validate(ActionRequest $request, array $arguments): FormSubmissionValidationResult
    {
        $errors = [];
        $validatedArguments = $arguments;

        $context = $this->formSubmissionContextResolver->resolveFormContext($request, $arguments);

        if (!$context instanceof NeosContext) {
            return new FormSubmissionValidationResult(
                $arguments,
                new FormSubmissionValidationErrorCollection(
                    new FormSubmissionValidationError('__general', 'The submitted form could not be resolved.'),
                ),
            );
        }

        foreach ($context->current->findFieldsRecursively() as $formField) {
            [$fieldErrors, $convertedValue, $fieldName] = $this->validateField($formField, $validatedArguments);
            $errors = [...$errors, ...$fieldErrors];

            if ($fieldName !== null) {
                $validatedArguments[$fieldName] = $convertedValue;
            }
        }

        return new FormSubmissionValidationResult(
            $validatedArguments,
            new FormSubmissionValidationErrorCollection(...$errors),
        );
    }

    /**
     * @param array<string, mixed> $arguments
     * @return array{0: array<int, FormSubmissionValidationError>, 1: mixed, 2: ?string}
     */
    private function validateField(FormField $field, array $arguments): array
    {
        $rawValue = $arguments[$field->name] ?? null;

        $schema = $this->fieldSchemaProviderResolver->resolve($field);

        $convertedValue = $schema->convert($rawValue);
        $effectiveValue = $convertedValue ?? $rawValue;
        $result = $schema->validate($effectiveValue);

        if (!$result->hasErrors()) {
            return [[], $effectiveValue, $field->name];
        }

        if ($field instanceof CustomErrorMessageProvider && $field->customErrorMessageEnabled && $field->customErrorMessage) {
            return [[new FormSubmissionValidationError($field->name, $field->customErrorMessage)], $effectiveValue, $field->name];
        }

        return [
            array_map(
                static fn (Error $error): FormSubmissionValidationError => new FormSubmissionValidationError(
                    fieldName: $field->name,
                    message: $error->render(),
                    validationId: $error instanceof ValidationError ? $error->validationId : null,
                ),
                $result->getErrors(),
            ),
            $effectiveValue,
            $field->name,
        ];
    }
}
