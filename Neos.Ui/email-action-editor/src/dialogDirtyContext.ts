import React from 'react';

type DialogDirtyContextValue = {
    isDirty: boolean;
    dirtyFields: Record<string, boolean>;
    contentDirty: boolean;
    formatDirty: boolean;
    neosFieldHighlights: Record<string, boolean>;
};

const defaultValue: DialogDirtyContextValue = {
    isDirty: false,
    dirtyFields: {},
    contentDirty: false,
    formatDirty: false,
    neosFieldHighlights: {}
};

export const DialogDirtyContext = React.createContext<DialogDirtyContextValue>(defaultValue);

export const useDialogDirtyContext = (): DialogDirtyContextValue =>
    React.useContext(DialogDirtyContext);

const DIALOG_DIRTY_FIELDS = [
    'subject',
    'recipientAddress',
    'senderAddress',
    'recipientName',
    'senderName',
    'replyToAddress',
    'carbonCopyAddress',
    'blindCarbonCopyAddress',
    'format',
    'html',
    'plaintext'
] as const;

const normalizeValue = (value: unknown): unknown => {
    if (value === null || typeof value === 'undefined') {
        return '';
    }
    return value;
};

const areEmailEntriesEqual = (
    left: Record<string, unknown>,
    right: Record<string, unknown>
): boolean => {
    const keys = new Set([...Object.keys(left), ...Object.keys(right)]);
    for (const key of keys) {
        if (normalizeValue(left[key]) !== normalizeValue(right[key])) {
            return false;
        }
    }
    return true;
};

type UseDialogDirtyValueParams = {
    baselineEntry: Record<string, unknown>;
    entry: Record<string, unknown>;
    format: string;
    neosFieldHighlights?: Record<string, boolean>;
};

export const useCreateDialogDirtyValue = ({
    baselineEntry,
    entry,
    format,
    neosFieldHighlights
}: UseDialogDirtyValueParams): DialogDirtyContextValue => {
    return React.useMemo(() => {
        const dirtyFields: Record<string, boolean> = {};
        for (const field of DIALOG_DIRTY_FIELDS) {
            dirtyFields[field] = normalizeValue(baselineEntry[field]) !== normalizeValue(entry[field]);
        }

        const normalizedNeosFieldHighlights = neosFieldHighlights ?? {};
        const contentDirty = format === 'html'
            ? dirtyFields.html === true
            : dirtyFields.plaintext === true;
        const formatDirty = dirtyFields.format === true || normalizedNeosFieldHighlights.format === true;

        return {
            isDirty: !areEmailEntriesEqual(baselineEntry, entry),
            dirtyFields,
            contentDirty,
            formatDirty,
            neosFieldHighlights: normalizedNeosFieldHighlights
        };
    }, [baselineEntry, entry, format, neosFieldHighlights]);
};
