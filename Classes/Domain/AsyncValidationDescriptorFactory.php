<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain;

use Sitegeist\PaperTiger\CPX\Domain\AsyncValidation\AsyncValidationRuleProviderRegistry;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Form\Form;

final class AsyncValidationDescriptorFactory
{
    public function __construct(
        private readonly AsyncValidationRuleProviderRegistry $ruleProviderRegistry,
    ) {
    }

    /**
     * @return list<array{name: string, validations: list<array<string, mixed>>}>
     */
    public function forForm(Form $form): array
    {
        $fields = [];

        foreach ($form->findFieldsRecursively() as $field) {
            $descriptor = $this->tryForField($field);
            if ($descriptor !== null) {
                $fields[] = $descriptor;
            }
        }

        return $fields;
    }

    /**
     * @return array{name: string, validations: list<array<string, mixed>>}|null
     */
    private function tryForField(FormField $field): ?array
    {
        $validations = [];
        foreach ($this->ruleProviderRegistry->all() as $provider) {
            foreach ($provider->forField($field) as $rule) {
                // Providers may optionally return the fieldName explicitly; if present and mismatching, ignore.
                $ruleFieldName = $rule['fieldName'] ?? null;
                if ($ruleFieldName !== null && $ruleFieldName !== $field->name) {
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
            'name' => $field->name,
            'validations' => array_values($byId),
        ];
    }
}
