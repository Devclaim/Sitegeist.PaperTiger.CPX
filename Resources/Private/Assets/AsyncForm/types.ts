export type ValidationRule = {
    validationId: string;
    message: string;
    value?: unknown;
    options?: Record<string, unknown>;
    earliestDate?: string | null;
    latestDate?: string | null;
};

export type ValidationField = {
    name: string;
    validations: ValidationRule[];
};

export type ValidationDescriptor = {
    formId: string;
    fields: ValidationField[];
};

export type ValidationError = {
    field: string;
    validationId: string;
    message: string;
};

export type SubmitErrorItem = {
    fieldName: string;
    message: string;
    validationId?: string | null;
};

export type SubmitErrorCollection = {
    items: SubmitErrorItem[];
};

export type SubmitResponse = {
    success: boolean;
    errors?: SubmitErrorItem[] | SubmitErrorCollection | null;
    redirectUri?: string | null;
    content?: string | null;
    message?: string | null;
};
