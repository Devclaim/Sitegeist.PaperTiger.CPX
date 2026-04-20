import type { ValidationError } from "./types";

const SUBMIT_ERROR_CLASS = "papertiger-field--submitError";
const GENERAL_ERROR_FIELD = "__general";

export function collectFormValues(form: HTMLFormElement): Record<string, unknown> {
    const values: Record<string, unknown> = {};

    const elements = Array.from(form.elements) as Array<
        HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement
    >;

    for (const el of elements) {
        if (!("name" in el)) continue;
        const name = (el as any).name as string;
        if (!name) continue;

        if (el instanceof HTMLInputElement) {
            if (el.type === "file") {
                values[name] = el.files;
                continue;
            }
            if (el.type === "checkbox") {
                if (!values[name]) values[name] = [];
                if (el.checked) (values[name] as any[]).push(el.value);
                continue;
            }
            if (el.type === "radio") {
                if (el.checked) values[name] = el.value;
                continue;
            }
            values[name] = el.value;
            continue;
        }

        values[name] = (el as any).value;
    }

    return values;
}

export function triggerSubmitAttemptAnimation(form: HTMLFormElement): void {
    const invalidFields = form.querySelectorAll<HTMLElement>(
        '[data-form-field].papertiger-field--invalid'
    );

    invalidFields.forEach((el) => el.classList.add(SUBMIT_ERROR_CLASS));

    window.setTimeout(() => {
        invalidFields.forEach((el) => el.classList.remove(SUBMIT_ERROR_CLASS));
    }, 320);
}

export function scrollToFirstError(form: HTMLFormElement, errors: ValidationError[]): void {
    const firstFieldError = errors.find((e) => e.field !== GENERAL_ERROR_FIELD);
    if (!firstFieldError) return;

    const container =
        findFieldContainer(form, firstFieldError.field) ??
        form.querySelector<HTMLElement>(`#fieldcontainer_${cssEscape(firstFieldError.field)}`);
    if (!container) return;

    container.scrollIntoView({ behavior: "smooth", block: "center" });
}

export function applyErrorsToDom(form: HTMLFormElement, errors: ValidationError[]): void {
    clearErrorsFromDom(form);

    const byField = new Map<string, ValidationError[]>();
    for (const error of errors) {
        const list = byField.get(error.field) ?? [];
        list.push(error);
        byField.set(error.field, list);
    }

    for (const [field, fieldErrors] of byField) {
        const container =
            findFieldContainer(form, field) ??
            form.querySelector<HTMLElement>(`#fieldcontainer_${cssEscape(field)}`);
        if (!container) continue;

        container.classList.add("papertiger-field--invalid");

        const slot = document.createElement("div");
        slot.setAttribute("data-papertiger-async-errors", "true");

        for (const err of fieldErrors) {
            const p = document.createElement("p");
            p.className = "papertiger-error";
            p.textContent = err.message;
            slot.appendChild(p);
        }

        container.appendChild(slot);
    }
}

export function clearErrorsFromDom(form: HTMLFormElement): void {
    form.querySelectorAll<HTMLElement>("[data-papertiger-async-errors]").forEach((el) =>
        el.remove()
    );
    form.querySelectorAll<HTMLElement>("[data-form-field]").forEach((el) => {
        el.classList.remove("papertiger-field--invalid");
    });
}

function findFieldContainer(form: HTMLFormElement, fieldName: string): HTMLElement | null {
    const el = form.querySelector<HTMLElement>(`[name="${CSS.escape(fieldName)}"]`);
    if (!el) return null;
    return el.closest<HTMLElement>("[data-form-field]");
}

function cssEscape(value: string): string {
    // Minimal escape; IDs are already sanitized field names in our setup.
    return value.replace(/[^a-zA-Z0-9_-]/g, "\\$&");
}
