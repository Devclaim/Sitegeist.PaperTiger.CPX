import type { ValidationDescriptor } from "./types";

export function readValidationDescriptorsFromDom(): ValidationDescriptor[] {
    const scripts = document.querySelectorAll<HTMLScriptElement>(
        'script[type="application/json"][data-papertiger-validation]'
    );

    const descriptors: ValidationDescriptor[] = [];

    scripts.forEach((script) => {
        try {
            const json = script.textContent || "{}";
            const parsed = JSON.parse(json) as Partial<ValidationDescriptor>;
            if (typeof parsed.formId === "string" && Array.isArray(parsed.fields)) {
                descriptors.push(parsed as ValidationDescriptor);
            }
        } catch {
        } finally {
            script.remove();
        }
    });

    return descriptors;
}

