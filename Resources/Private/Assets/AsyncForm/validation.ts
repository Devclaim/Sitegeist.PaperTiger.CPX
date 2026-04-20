import type { ValidationDescriptor, ValidationError, ValidationRule } from "./types";
import { getAsyncValidator } from "./validatorRegistry";

type CompiledRule = {
    validationId: string;
    message: string;
    validate: (value: unknown) => boolean;
};

function compileRule(rule: ValidationRule): CompiledRule | null {
    const validator = getAsyncValidator(rule.validationId);
    if (!validator) return null;

    return {
        validationId: rule.validationId,
        message: rule.message,
        validate: (value) => validator(value, rule),
    };
}

export function buildFieldSchemas(descriptor: ValidationDescriptor): Record<string, CompiledRule[]> {
    const fieldSchemas: Record<string, CompiledRule[]> = {};

    for (const field of descriptor.fields || []) {
        const compiled: CompiledRule[] = [];
        for (const rule of field.validations) {
            const c = compileRule(rule);
            if (c) compiled.push(c);
        }
        fieldSchemas[field.name] = compiled;
    }

    return fieldSchemas;
}

export function validateValues(
    fieldSchemas: Record<string, CompiledRule[]>,
    values: Record<string, unknown>
): ValidationError[] {
    const errors: ValidationError[] = [];
    for (const field of Object.keys(fieldSchemas)) {
        for (const compiled of fieldSchemas[field] || []) {
            if (compiled.validate(values[field])) continue;
            errors.push({
                field,
                validationId: compiled.validationId,
                message: compiled.message,
            });
        }
    }
    return errors;
}

export function validateField(
    fieldSchemas: Record<string, CompiledRule[]>,
    field: string,
    values: Record<string, unknown>
): ValidationError[] {
    const compiledRules = fieldSchemas[field];
    if (!compiledRules) return [];

    const errors: ValidationError[] = [];
    for (const compiled of compiledRules) {
        if (compiled.validate(values[field])) continue;
        errors.push({
            field,
            validationId: compiled.validationId,
            message: compiled.message,
        });
    }
    return errors;
}
