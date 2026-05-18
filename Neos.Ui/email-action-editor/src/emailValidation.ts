export type EmailValidationResult = {
    isValid: boolean;
    fieldWarnings: Record<string, string | undefined>;
};

const EMAIL_FIELDS_REQUIRED = ['senderAddress', 'recipientAddress'] as const;
const EMAIL_FIELDS_OPTIONAL = ['replyToAddress', 'carbonCopyAddress', 'blindCarbonCopyAddress'] as const;

const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

const splitEmails = (value: string): string[] =>
    value
        .split(/[,\n;]/)
        .map(part => part.trim())
        .filter(Boolean);

const hasInvalidEmails = (value: string): boolean =>
    splitEmails(value).some(email => !EMAIL_REGEX.test(email));

export const validateEmailEntry = (entry: Record<string, unknown>): EmailValidationResult => {
    const fieldWarnings: Record<string, string | undefined> = {};

    for (const field of EMAIL_FIELDS_REQUIRED) {
        const value = typeof entry[field] === 'string' ? entry[field].trim() : '';
        if (!value) {
            fieldWarnings[field] = 'Required email address.';
            continue;
        }
        if (hasInvalidEmails(value)) {
            fieldWarnings[field] = 'Invalid email address.';
        }
    }

    for (const field of EMAIL_FIELDS_OPTIONAL) {
        const value = typeof entry[field] === 'string' ? entry[field].trim() : '';
        if (!value) {
            continue;
        }
        if (hasInvalidEmails(value)) {
            fieldWarnings[field] = 'Invalid email address.';
        }
    }

    return {
        isValid: Object.values(fieldWarnings).every(value => typeof value === 'undefined'),
        fieldWarnings
    };
};
