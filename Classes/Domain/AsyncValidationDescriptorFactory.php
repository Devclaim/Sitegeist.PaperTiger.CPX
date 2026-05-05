<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain;

use Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindChildNodesFilter;
use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\AsyncValidation\AsyncValidationRuleProviderRegistry;

final class AsyncValidationDescriptorFactory
{
    public function __construct(
        private readonly AsyncValidationRuleProviderRegistry $ruleProviderRegistry,
    ) {
    }

    /**
     * @return list<array{name: string, validations: list<array<string, mixed>>}>
     */
    public function forForm(NeosContext $context): array
    {
        return $this->collectFieldDescriptors($context, $context->node);
    }

    /**
     * @return list<array{name: string, validations: list<array<string, mixed>>}>
     */
    private function collectFieldDescriptors(NeosContext $context, Node $parentNode): array
    {
        $fields = [];

        foreach ($context->subgraph->findChildNodes($parentNode->aggregateId, FindChildNodesFilter::create()) as $fieldNode) {
            $nodeType = $context->nodes->tryGetNodeType($fieldNode);
            if ($nodeType === null) {
                continue;
            }

            if ($nodeType->isOfType('Sitegeist.PaperTiger.CPX:Fieldset')) {
                $fields = [...$fields, ...$this->collectFieldDescriptors($context, $fieldNode)];
                continue;
            }

            if ($nodeType->isOfType('Sitegeist.PaperTiger.CPX:Field')) {
                $descriptor = $this->forField($context, $fieldNode);
                if ($descriptor !== null) {
                    $fields[] = $descriptor;
                }
            }
        }

        return $fields;
    }

    /**
     * @return array{name: string, validations: list<array<string, mixed>>}|null
     */
    private function forField(NeosContext $context, Node $fieldNode): ?array
    {
        $nodeType = $context->nodes->tryGetNodeType($fieldNode);
        if ($nodeType === null) {
            return null;
        }

        $name = $context->nodes->getStringValue($fieldNode, 'name') ?? $fieldNode->aggregateId->value;

        $validations = [];
        foreach ($this->ruleProviderRegistry->all() as $provider) {
            foreach ($provider->forField($context, $fieldNode) as $rule) {
                // Providers may optionally return the fieldName explicitly; if present and mismatching, ignore.
                $ruleFieldName = $rule['fieldName'] ?? null;
                if ($ruleFieldName !== null && $ruleFieldName !== $name) {
                    continue;
                }

                unset($rule['fieldName']);
                $validations[] = $rule;
            }
        }

        if ($validations === []) {
            return null;
        }

        // Last provider wins by validationId.
        $byId = [];
        foreach ($validations as $rule) {
            $id = $rule['validationId'] ?? null;
            if (is_string($id) && $id !== '') {
                $byId[$id] = $rule;
            }
        }

        return [
            'name' => $name,
            'validations' => array_values($byId),
        ];
    }
}
