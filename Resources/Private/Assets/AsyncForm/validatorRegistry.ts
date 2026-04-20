import type { ValidationRule } from "./types";

export type AsyncValidator = (value: unknown, rule: ValidationRule) => boolean;

const registry = new Map<string, AsyncValidator>();

export function registerAsyncValidator(validationId: string, validator: AsyncValidator): void {
    if (!validationId) return;
    registry.set(validationId, validator);
}

export function getAsyncValidator(validationId: string): AsyncValidator | undefined {
    return registry.get(validationId);
}

// Built-in validators
function isEmptyValue(value: unknown): boolean {
    return (
        value === null ||
        value === undefined ||
        value === "" ||
        (Array.isArray(value) && value.length === 0) ||
        (value instanceof FileList && value.length === 0)
    );
}

function normalizeFiles(value: unknown): File[] {
    if (value instanceof FileList) return Array.from(value);
    if (value instanceof File) return [value];
    return [];
}

registerAsyncValidator("required", (value) => !isEmptyValue(value));

registerAsyncValidator("minLength", (value, rule) => {
    const min =
        typeof rule.options?.minimum === "number"
            ? rule.options.minimum
            : typeof rule.value === "number"
              ? rule.value
              : null;
    if (min === null) return true;
    if (isEmptyValue(value)) return true;
    return String(value).length >= min;
});

registerAsyncValidator("maxLength", (value, rule) => {
    const max =
        typeof rule.options?.maximum === "number"
            ? rule.options.maximum
            : typeof rule.value === "number"
              ? rule.value
              : null;
    if (max === null) return true;
    if (isEmptyValue(value)) return true;
    return String(value).length <= max;
});

registerAsyncValidator("pattern", (value, rule) => {
    const fromOptions =
        typeof rule.options?.regularExpression === "string" ? rule.options.regularExpression : null;
    const raw = fromOptions ?? (typeof rule.value === "string" ? rule.value : null);
    if (!raw) return true;
    if (isEmptyValue(value)) return true;
    // PHP schema uses /^...$/ for Flow; normalize for JS.
    const pattern =
        raw.startsWith("/^") && raw.endsWith("$/") ? raw.slice(2, -2) : raw;
    try {
        const re = new RegExp(`^(?:${pattern})$`);
        return re.test(String(value));
    } catch {
        // invalid regex configured: don't block submit client-side
        return true;
    }
});

registerAsyncValidator("uploadSize", (value, rule) => {
    const maxBytes =
        typeof rule.options?.maximumSize === "number"
            ? rule.options.maximumSize
            : typeof rule.value === "number"
              ? rule.value
              : null;
    if (maxBytes === null) return true;
    const files = normalizeFiles(value);
    return files.every((f) => f.size <= maxBytes);
});

registerAsyncValidator("uploadType", (value, rule) => {
    const allowed = Array.isArray(rule.options?.allowedExtensions)
        ? rule.options.allowedExtensions
        : Array.isArray(rule.value)
          ? rule.value
          : null;
    if (!allowed) return true;
    const normalized = allowed
        .map((x) => (typeof x === "string" ? x.toLowerCase() : ""))
        .filter(Boolean);
    if (normalized.length === 0) return true;

    const files = normalizeFiles(value);
    return files.every((file) => {
        const ext = (file.name.split(".").pop() || "").toLowerCase();
        return ext === "" || normalized.includes(ext);
    });
});

registerAsyncValidator("dateRange", (value, rule) => {
    if (isEmptyValue(value)) return true;
    const s = String(value);
    if (!/^\d{4}-\d{2}-\d{2}$/.test(s)) return false;
    const earliest =
        (typeof rule.options?.earliestDate === "string" ? rule.options.earliestDate : null) ??
        rule.earliestDate ??
        null;
    const latest =
        (typeof rule.options?.latestDate === "string" ? rule.options.latestDate : null) ??
        rule.latestDate ??
        null;
    if (earliest && s < earliest) return false;
    if (latest && s > latest) return false;
    return true;
});
