import React from 'react';
import {autocompletion, closeBrackets} from '@codemirror/autocomplete';
import {indentWithTab} from '@codemirror/commands';
import {html} from '@codemirror/lang-html';
import {bracketMatching} from '@codemirror/language';
import {EditorState} from '@codemirror/state';
import {
    drawSelection,
    EditorView,
    highlightActiveLineGutter,
    keymap,
    lineNumbers
} from '@codemirror/view';
import {vscodeDark} from '@uiw/codemirror-theme-vscode';

type HtmlCodeEditorProps = {
    value: string;
    onChange: (value: string) => void;
    onFocus: () => void;
    editorRef: React.MutableRefObject<EditorView | null>;
};

export const HtmlCodeEditor = React.memo((props: HtmlCodeEditorProps) => {
    const {value, onChange, onFocus, editorRef} = props;
    const containerRef = React.useRef<HTMLDivElement | null>(null);
    const isApplyingExternalUpdateRef = React.useRef(false);

    React.useEffect(() => {
        if (!containerRef.current) {
            return;
        }

        const view = new EditorView({
            state: EditorState.create({
                doc: value,
                extensions: [
                    lineNumbers(),
                    highlightActiveLineGutter(),
                    drawSelection(),
                    vscodeDark,
                    EditorView.theme({
                        '&': {height: '100%', maxHeight: '100%'},
                        '.cm-scroller': {overflow: 'auto'}
                    }),
                    keymap.of([indentWithTab]),
                    bracketMatching(),
                    closeBrackets(),
                    autocompletion(),
                    html({
                        autoCloseTags: true
                    }),
                    EditorView.domEventHandlers({
                        focusin: () => {
                            onFocus();
                        }
                    }),
                    EditorView.updateListener.of((update) => {
                        if (update.docChanged && !isApplyingExternalUpdateRef.current) {
                            onChange(update.state.doc.toString());
                        }
                    })
                ]
            }),
            parent: containerRef.current
        });

        editorRef.current = view;

        return () => {
            if (editorRef.current === view) {
                editorRef.current = null;
            }
            view.destroy();
        };
    }, [editorRef, onChange, onFocus]);

    React.useEffect(() => {
        const view = editorRef.current;
        if (!view) {
            return;
        }

        const currentValue = view.state.doc.toString();
        if (currentValue === value) {
            return;
        }

        isApplyingExternalUpdateRef.current = true;
        view.dispatch({
            changes: {
                from: 0,
                to: currentValue.length,
                insert: value
            }
        });
        isApplyingExternalUpdateRef.current = false;
    }, [editorRef, value]);

    return <div ref={containerRef} style={{flex: 1, minHeight: 0, overflow: 'hidden'}} />;
});
