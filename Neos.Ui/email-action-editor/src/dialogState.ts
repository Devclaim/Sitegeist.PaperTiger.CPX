import React from 'react';

export type EmailActionEntry = Record<string, unknown>;
export type EmailActionFieldHighlights = Record<string, boolean>;

export type EmailActionFieldToken = {
    name: string;
    label: string;
    token: string;
};

type EmailActionDialogPayload = {
    baselineValue?: EmailActionEntry;
    value?: EmailActionEntry;
    neosHighlight?: boolean;
    neosFieldHighlights?: EmailActionFieldHighlights;
    index: number;
    fieldTokens?: Array<EmailActionFieldToken>;
    onApply: (index: number, nextValue: EmailActionEntry) => void;
};

type EmailActionDialogState = {
    isOpen: boolean;
    payload: EmailActionDialogPayload | null;
};

const listeners = new Set<() => void>();

let state: EmailActionDialogState = {
    isOpen: false,
    payload: null
};

const emitChange = (): void => {
    listeners.forEach(listener => listener());
};

export const openEmailActionDialog = (payload: EmailActionDialogPayload): void => {
    state = {
        isOpen: true,
        payload
    };
    emitChange();
};

export const closeEmailActionDialog = (): void => {
    state = {
        isOpen: false,
        payload: null
    };
    emitChange();
};

const subscribe = (listener: () => void): (() => void) => {
    listeners.add(listener);

    return () => {
        listeners.delete(listener);
    };
};

const getSnapshot = (): EmailActionDialogState => state;

export const useEmailActionDialogState = (): EmailActionDialogState => {
    const [snapshot, setSnapshot] = React.useState<EmailActionDialogState>(getSnapshot());

    React.useEffect(() => subscribe(() => setSnapshot(getSnapshot())), []);

    return snapshot;
};
