import React from 'react';
import {EditorView} from '@codemirror/view';

import {EmailFormat} from '../useEmailActionEditor';
import {HtmlCodeEditor} from './HtmlCodeEditor';
import {TokenBar} from './TokenBar';
import {
    EditorSurface,
    Pane,
    PlaintextEditor
} from './EmailActionDialog.styles';

type EditorPaneProps = {
    fieldTokens: Array<{ name: string; label: string; token: string }>;
    format: EmailFormat;
    htmlValue: string;
    plaintextValue: string;
    onFocusHtml: () => void;
    onFocusPlaintext: () => void;
    onHtmlChange: (value: string) => void;
    onInsertToken: (token: string) => void;
    onPlaintextChange: (value: string) => void;
    plaintextInputRef: React.RefObject<HTMLTextAreaElement>;
    htmlEditorRef: React.MutableRefObject<EditorView | null>;
};

export const EditorPane = React.memo((props: EditorPaneProps) => {
    const {
        fieldTokens,
        format,
        htmlValue,
        plaintextValue,
        onFocusHtml,
        onFocusPlaintext,
        onHtmlChange,
        onInsertToken,
        onPlaintextChange,
        plaintextInputRef,
        htmlEditorRef
    } = props;

    return (
        <Pane>
            <TokenBar
                fieldTokens={fieldTokens}
                onInsertToken={onInsertToken}
            />
            <EditorSurface>
                {format === 'html' ? (
                    <HtmlCodeEditor
                        value={htmlValue}
                        onFocus={onFocusHtml}
                        onChange={onHtmlChange}
                        editorRef={htmlEditorRef}
                    />
                ) : (
                    <PlaintextEditor
                        ref={plaintextInputRef}
                        value={plaintextValue}
                        onFocus={onFocusPlaintext}
                        onChange={(event) => onPlaintextChange(event.target.value)}
                    />
                )}
            </EditorSurface>
        </Pane>
    );
}, (previousProps, nextProps) => (
    previousProps.format === nextProps.format &&
    previousProps.htmlValue === nextProps.htmlValue &&
    previousProps.plaintextValue === nextProps.plaintextValue &&
    previousProps.fieldTokens === nextProps.fieldTokens &&
    previousProps.onFocusHtml === nextProps.onFocusHtml &&
    previousProps.onFocusPlaintext === nextProps.onFocusPlaintext &&
    previousProps.onHtmlChange === nextProps.onHtmlChange &&
    previousProps.onInsertToken === nextProps.onInsertToken &&
    previousProps.onPlaintextChange === nextProps.onPlaintextChange
));
