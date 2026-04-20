<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain;

use Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindChildNodesFilter;
use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\ContentRepository\Core\SharedModel\Node\NodeName;
use Neos\Error\Messages\Error;
use Neos\Flow\Mvc\ActionRequest;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\Schema\FieldSchemaProviderResolver;
use Sitegeist\PaperTiger\CPX\Domain\Validation\ValidationError;
use Sitegeist\PaperTiger\CPX\Dto\FormSubmissionValidationError;
use Sitegeist\PaperTiger\CPX\Dto\FormSubmissionValidationErrorCollection;

final class FormSubmissionValidator
{
    public function __construct(
        private readonly FormSubmissionContextResolver $formSubmissionContextResolver,
        private readonly FieldSchemaProviderResolver $fieldSchemaProviderResolver,
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

        foreach ($this->resolveFieldNodes($context) as $fieldNode) {
            [$fieldErrors, $convertedValue, $fieldName] = $this->validateField($context, $fieldNode, $validatedArguments);
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
     * @return array<int, Node>
     */
    private function resolveFieldNodes(NeosContext $context): array
    {
        $fieldsCollectionNode = $context->subgraph->findNodeByPath(NodeName::fromString('fields'), $context->node->aggregateId);
        if (!$fieldsCollectionNode instanceof Node) {
            return [];
        }

        return $this->collectFieldNodes($context, $fieldsCollectionNode);
    }

    /**
     * @return array<int, Node>
     */
    private function collectFieldNodes(NeosContext $context, Node $collectionNode): array
    {
        $fieldNodes = [];

        foreach ($context->subgraph->findChildNodes($collectionNode->aggregateId, FindChildNodesFilter::create()) as $childNode) {
            $nodeType = $context->nodes->tryGetNodeType($childNode);

            if ($nodeType?->isOfType('Sitegeist.PaperTiger.CPX:Fieldset')) {
                $fieldNodes = [...$fieldNodes, ...$this->collectFieldNodes($context, $childNode)];
                continue;
            }

            if ($nodeType?->isOfType('Sitegeist.PaperTiger.CPX:Field')) {
                $fieldNodes[] = $childNode;
            }
        }

        return $fieldNodes;
    }

    /**
     * @param array<string, mixed> $arguments
     * @return array{0: array<int, FormSubmissionValidationError>, 1: mixed, 2: ?string}
     */
    private function validateField(NeosContext $context, Node $fieldNode, array $arguments): array
    {
        $nodeType = $context->nodes->tryGetNodeType($fieldNode);
        if ($nodeType === null) {
            return [[], null, null];
        }

        $fieldName = $this->readString($context, $fieldNode, 'name') ?? $fieldNode->aggregateId->value;
        $rawValue = $arguments[$fieldName] ?? null;

        $schema = $this->fieldSchemaProviderResolver->resolve($context, $fieldNode);
        if ($schema === null) {
            return [[], $rawValue, $fieldName];
        }

        $convertedValue = $schema->convert($rawValue);
        $validationValue = $convertedValue ?? $rawValue;
        $result = $schema->validate($validationValue);

        if (!$result->hasErrors()) {
            return [[], $convertedValue, $fieldName];
        }

        $customErrorMessageEnabled = $this->readBool($context, $fieldNode, 'customErrorMessageEnabled') ?? false;
        $customErrorMessage = $this->readString($context, $fieldNode, 'customErrorMessage');

        if ($customErrorMessageEnabled && is_string($customErrorMessage) && $customErrorMessage !== '') {
            return [[new FormSubmissionValidationError($fieldName, $customErrorMessage)], $convertedValue, $fieldName];
        }

        return [
            array_map(
                static fn (Error $error): FormSubmissionValidationError => new FormSubmissionValidationError(
                    fieldName: $fieldName,
                    message: $error->render(),
                    validationId: $error instanceof ValidationError ? $error->validationId : null,
                ),
                $result->getErrors(),
            ),
            $convertedValue,
            $fieldName,
        ];
    }

    private function readString(NeosContext $context, Node $node, string $propertyName): ?string
    {
        return $context->nodes->getStringValue($node, $propertyName);
    }

    private function readBool(NeosContext $context, Node $node, string $propertyName): ?bool
    {
        return $context->nodes->getBoolValue($node, $propertyName);
    }
}
