import { createStore } from "zustand/vanilla";
import type { ValidationError } from "./types";

export type AsyncFormState = {
    values: Record<string, unknown>;
    errors: ValidationError[];
    touched: Record<string, boolean>;
};

export type AsyncFormActions = {
    setValue(field: string, value: unknown): void;
    setTouched(field: string, touched?: boolean): void;
    setErrors(errors: ValidationError[]): void;
    clearErrors(): void;
};

export type AsyncFormStore = AsyncFormState & AsyncFormActions;

export function createAsyncFormStore(initialValues: Record<string, unknown> = {}) {
    return createStore<AsyncFormStore>((set) => ({
        values: initialValues,
        errors: [],
        touched: {},
        setValue: (field, value) =>
            set((s) => ({
                values: { ...s.values, [field]: value },
            })),
        setTouched: (field, touched = true) =>
            set((s) => ({
                touched: { ...s.touched, [field]: touched },
            })),
        setErrors: (errors) => set(() => ({ errors })),
        clearErrors: () => set(() => ({ errors: [] })),
    }));
}

