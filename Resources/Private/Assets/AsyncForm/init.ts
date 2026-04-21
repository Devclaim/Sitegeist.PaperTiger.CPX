import { readValidationDescriptorsFromDom } from "./descriptor";
import { buildFieldSchemas, validateField, validateValues } from "./validation";
import {
    applyErrorsToDom,
    clearErrorsFromDom,
    collectFormValues,
    scrollToFirstError,
    triggerSubmitAttemptAnimation,
} from "./dom";
import { createAsyncFormStore } from "./store";
import type { SubmitErrorItem, SubmitResponse } from "./types";

function normalizeSubmitErrors(json: SubmitResponse | null | undefined): SubmitErrorItem[] {
    const errors = json?.errors ?? null;
    if (Array.isArray(errors)) {
        return errors;
    }
    if (errors && typeof errors === "object" && Array.isArray((errors as any).items)) {
        return (errors as any).items as SubmitErrorItem[];
    }
    return [];
}

function fallbackSubmitResponse(message: string): SubmitResponse {
    return {
        success: false,
        errors: [
            {
                fieldName: "__general",
                message,
                validationId: "server",
            },
        ],
    };
}

function replaceFormWithHtml(form: HTMLFormElement, html: string): void {
    const wrapper = document.createElement("div");
    wrapper.setAttribute("data-papertiger-submit-message", "true");
    wrapper.innerHTML = html;
    form.replaceWith(wrapper);
}

function setSubmitDisabled(form: HTMLFormElement, disabled: boolean): void {
    form.querySelectorAll<HTMLButtonElement>('button[type="submit"]').forEach((el) => {
        el.disabled = disabled;
    });
}

async function submitForm(
    form: HTMLFormElement,
    values: Record<string, unknown>
): Promise<{ ok: boolean; status: number; json: SubmitResponse }> {
    const body = new FormData(form);
    // Ensure current values are submitted (even if JS touched state differs).
    for (const [name, value] of Object.entries(values)) {
        if (value instanceof FileList) continue;
        if (Array.isArray(value)) continue;
        body.set(name, value === null || value === undefined ? "" : String(value));
    }

    const res = await fetch("/papertiger/submit", {
        method: "POST",
        headers: {
            Accept: "application/json",
        },
        body,
    });

    let json: SubmitResponse;
    try {
        json = (await res.json()) as SubmitResponse;
    } catch {
        json = fallbackSubmitResponse("The form could not be submitted.");
    }

    return { ok: res.ok, status: res.status, json };
}

export function initAsyncForms(): void {
    const descriptors = readValidationDescriptorsFromDom();
    if (descriptors.length === 0) return;

    for (const descriptor of descriptors) {
        const form = document.getElementById(descriptor.formId) as HTMLFormElement | null;
        if (!form) continue;

        const SUBMIT_LOADING_CLASS = "papertiger-form--submitLoading";

        const fieldSchemas = buildFieldSchemas(descriptor);
        const store = createAsyncFormStore();
        const hasBlurred = new Set<string>();

        store.subscribe((state) => {
            if (state.errors.length === 0) {
                clearErrorsFromDom(form);
            } else {
                applyErrorsToDom(form, state.errors);
            }
        });

        const validateAndStoreField = (field: string): void => {
            const fieldErrors = validateField(fieldSchemas, field, store.getState().values);
            const otherErrors = store.getState().errors.filter((e) => e.field !== field);
            store.getState().setErrors([...otherErrors, ...fieldErrors]);
        };

        for (const field of Object.keys(fieldSchemas)) {
            const elements = form.querySelectorAll<
                HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement
            >(`[name="${CSS.escape(field)}"]`);
            if (elements.length === 0) continue;

            elements.forEach((el) => {
                const syncValue = () => {
                    const values = collectFormValues(form);
                    store.getState().setValue(field, values[field]);
                };

                el.addEventListener("input", () => {
                    syncValue();
                    if (hasBlurred.has(field)) {
                        validateAndStoreField(field);
                    }
                });

                el.addEventListener("change", () => {
                    syncValue();
                    if (hasBlurred.has(field)) {
                        validateAndStoreField(field);
                    }
                });

                el.addEventListener("blur", () => {
                    syncValue();
                    hasBlurred.add(field);
                    store.getState().setTouched(field, true);
                    validateAndStoreField(field);
                });
            });
        }

        form.addEventListener("submit", (e) => {
            e.preventDefault();

            const values = collectFormValues(form);
            Object.keys(values).forEach((key) => store.getState().setValue(key, values[key]));
            store.getState().clearErrors();
            store.getState().setErrors(validateValues(fieldSchemas, values));
            const errors = store.getState().errors;
            if (errors.length > 0) {
                for (const field of Object.keys(fieldSchemas)) {
                    hasBlurred.add(field);
                    store.getState().setTouched(field, true);
                }

                scrollToFirstError(form, errors);
                triggerSubmitAttemptAnimation(form);
                return;
            }

            void (async () => {
                form.classList.add(SUBMIT_LOADING_CLASS);
                setSubmitDisabled(form, true);
                try {
                    const response = await submitForm(form, values);

                    const json = response.json;
                    const serverErrors = normalizeSubmitErrors(json).map((it) => ({
                        field: it.fieldName,
                        validationId: it.validationId ?? "server",
                        message: it.message,
                    }));

                    store.getState().setErrors(serverErrors);

                    if (response.ok === false || json?.success === false || serverErrors.length > 0) {
                        for (const field of Object.keys(fieldSchemas)) {
                            hasBlurred.add(field);
                            store.getState().setTouched(field, true);
                        }

                        if (serverErrors.length > 0) {
                            scrollToFirstError(form, store.getState().errors);
                            triggerSubmitAttemptAnimation(form);
                        }

                        return;
                    }

                    if (typeof json.redirectUri === "string" && json.redirectUri !== "") {
                        window.location.href = json.redirectUri;
                        return;
                    }

                    if (json.success === true && typeof json.message === "string" && json.message !== "") {
                        replaceFormWithHtml(form, json.message);
                        return;
                    }

                    // TODO: handle json.content / json.message in the DOM
                } catch {
                    store.getState().setErrors([
                        {
                            field: "__general",
                            validationId: "server",
                            message: "The form could not be submitted.",
                        },
                    ]);
                    triggerSubmitAttemptAnimation(form);
                } finally {
                    // If the form was replaced by a success message, it is no longer connected.
                    if (form.isConnected) {
                        form.classList.remove(SUBMIT_LOADING_CLASS);
                        setSubmitDisabled(form, false);
                    }
                }
            })();
        });

        // For debugging
        (window as any).__paperTigerAsyncValidation = {
            descriptor,
            fieldSchemas,
            store,
        };
    }
}
