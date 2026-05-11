import React from 'react';
import {EditorSelection} from '@codemirror/state';
import {EditorView} from '@codemirror/view';

export type EntryField =
    | 'subject'
    | 'recipientAddress'
    | 'recipientName'
    | 'senderAddress'
    | 'senderName'
    | 'replyToAddress'
    | 'carbonCopyAddress'
    | 'blindCarbonCopyAddress'
    | 'plaintext';

export type InsertTarget = 'subject' | 'plaintext' | 'html';
export type EmailFormat = 'plaintext' | 'html';
export type EditableField = EntryField | 'html' | 'format';
export type SetFieldValue = (field: EditableField, value: string) => void;

type UseEmailActionEditorParams = {
    activeTarget: InsertTarget;
    entry: Record<string, unknown>;
    htmlEditorRef: React.MutableRefObject<EditorView | null>;
    plaintextInputRef: React.RefObject<HTMLTextAreaElement | null>;
    setActiveTarget: React.Dispatch<React.SetStateAction<InsertTarget>>;
    setEntry: React.Dispatch<React.SetStateAction<Record<string, unknown>>>;
    subjectInputRef: React.RefObject<HTMLInputElement | null>;
};

const getStringValue = (value: unknown): string =>
    typeof value === 'string' ? value : '';

export const useEmailActionEditor = ({
    activeTarget,
    entry,
    htmlEditorRef,
    plaintextInputRef,
    setActiveTarget,
    setEntry,
    subjectInputRef
}: UseEmailActionEditorParams) => {
    const setFieldValue = React.useCallback<SetFieldValue>((field, value): void => {
        setEntry((currentEntry) => ({
            ...currentEntry,
            [field]: value
        }));
    }, [setEntry]);

    const handleHtmlFocus = React.useCallback((): void => {
        setActiveTarget('html');
    }, [setActiveTarget]);

    const handlePlaintextFocus = React.useCallback((): void => {
        setActiveTarget('plaintext');
    }, [setActiveTarget]);

    const updateTextWithInsertion = React.useCallback((
        currentValue: string,
        token: string,
        input: HTMLInputElement | HTMLTextAreaElement | null,
        field: EntryField
    ): void => {
        if (!input) {
            setFieldValue(field, `${currentValue}${token}`);
            return;
        }

        const start = input.selectionStart ?? currentValue.length;
        const end = input.selectionEnd ?? currentValue.length;
        const nextValue = `${currentValue.slice(0, start)}${token}${currentValue.slice(end)}`;

        setFieldValue(field, nextValue);

        window.requestAnimationFrame(() => {
            input.focus();
            const cursor = start + token.length;
            input.setSelectionRange(cursor, cursor);
        });
    }, [setFieldValue]);

    const insertToken = React.useCallback((token: string): void => {
        if (activeTarget === 'subject') {
            updateTextWithInsertion(
                getStringValue(entry.subject),
                token,
                subjectInputRef.current,
                'subject'
            );
            return;
        }

        if (activeTarget === 'plaintext') {
            updateTextWithInsertion(
                getStringValue(entry.plaintext),
                token,
                plaintextInputRef.current,
                'plaintext'
            );
            return;
        }

        const editor = htmlEditorRef.current;
        const currentValue = getStringValue(entry.html);
        if (!editor) {
            setFieldValue('html', `${currentValue}${token}`);
            return;
        }

        const selection = editor.state.selection.main;
        editor.dispatch({
            changes: {
                from: selection.from,
                to: selection.to,
                insert: token
            },
            selection: EditorSelection.cursor(selection.from + token.length),
            scrollIntoView: true
        });
        editor.focus();
    }, [
        activeTarget,
        entry.html,
        entry.plaintext,
        entry.subject,
        htmlEditorRef,
        plaintextInputRef,
        setFieldValue,
        subjectInputRef,
        updateTextWithInsertion
    ]);

    const handleHtmlChange = React.useCallback((value: string): void => {
        setFieldValue('html', value);
    }, [setFieldValue]);

    const handlePlaintextChange = React.useCallback((value: string): void => {
        setFieldValue('plaintext', value);
    }, [setFieldValue]);

    const format: EmailFormat = entry.format === 'html' ? 'html' : 'plaintext';
    const previewMarkup =
        getStringValue(entry.html).trim().length > 0 ? getStringValue(entry.html) : '';
    const previewPlaintext = getStringValue(entry.plaintext);
    const subject = getStringValue(entry.subject);

    return {
        format,
        handleHtmlChange,
        handleHtmlFocus,
        handlePlaintextChange,
        handlePlaintextFocus,
        insertToken,
        previewMarkup,
        previewPlaintext,
        setFieldValue,
        subject
    };
};
