import React from 'react';
import {
    Panel,
    PanelGroup,
    PanelResizeHandle
} from 'react-resizable-panels';
import {EditorView} from '@codemirror/view';

import {Button, Dialog, Icon} from '@neos-project/react-ui-components';
import {useI18n} from '@sitegeist/papertiger-cpx-neos-bridge';

import {closeEmailActionDialog, useEmailActionDialogState} from './dialogState';
import {AddressBar} from './components/AddressBar';
import {EditorPane} from './components/EditorPane';
import {EmailCompatibilityPane} from './components/EmailCompatibilityPane';
import {PreviewPane} from './components/PreviewPane';
import {
    deriveClients,
    useCompatibilityData
} from './components/emailCompatibilityRemote';
import {useCompatibilityAnalysis} from './components/emailCompatibilityCompute';
import {
    Container,
    EditorLayout,
    FormatToggle,
    FormatToggleSide,
    ResizeHandleVisual,
    ToolbarRow,
    dialogStyles
} from './components/EmailActionDialog.styles';
import {InsertTarget, useEmailActionEditor} from './useEmailActionEditor';

const EDITOR_LAYOUT_STORAGE_KEY =
    'Sitegeist.PaperTiger.CPX.EmailActionDialog.layout';

export const EmailActionDialogContainer: React.FC = () => {
    const t = useI18n();
    const {isOpen, payload} = useEmailActionDialogState();
    const [entry, setEntry] = React.useState<Record<string, unknown>>({});
    const [activeTarget, setActiveTarget] = React.useState<InsertTarget>('html');
    const htmlEditorRef = React.useRef<EditorView | null>(null);
    const subjectInputRef = React.useRef<HTMLInputElement | null>(null);
    const plaintextInputRef = React.useRef<HTMLTextAreaElement | null>(null);
    const fieldTokens = payload?.fieldTokens ?? [];

    React.useEffect(() => {
        if (isOpen) {
            setEntry(payload?.value ?? {});
            setActiveTarget(
                (payload?.value?.format ?? 'plaintext') === 'html' ? 'html' : 'plaintext'
            );
        }
    }, [isOpen, payload]);

    const {
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
    } = useEmailActionEditor({
        activeTarget,
        entry,
        htmlEditorRef,
        plaintextInputRef,
        setActiveTarget,
        setEntry,
        subjectInputRef
    });

    const compatibility = useCompatibilityData();
    const clients = React.useMemo(
        () => (compatibility.data ? deriveClients(compatibility.data) : []),
        [compatibility.data]
    );

    const html = typeof entry.html === 'string' ? entry.html : '';
    const analysis = useCompatibilityAnalysis(html, compatibility, clients);

    const [activeView, setActiveView] = React.useState<'editor' | 'compatibility'>('editor');

    React.useEffect(() => {
        if (format !== 'html' && activeView === 'compatibility') {
            setActiveView('editor');
        }
    }, [format, activeView]);

    if (!isOpen || !payload) {
        return null;
    }

    const handleApply = (): void => {
        payload.onApply(payload.index, entry);
        closeEmailActionDialog();
    };

    return (
        <>
            <style>{dialogStyles}</style>
            <Dialog
                isOpen
                className="papertiger-email-dialog"
                style="jumbo"
                onRequestClose={closeEmailActionDialog}
                actions={[
                    <Button type="button" onClick={closeEmailActionDialog}>
                        {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:dialog.discard')}
                    </Button>,
                    <Button style="success" type="button" onClick={handleApply}>
                        {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:dialog.apply')}
                    </Button>
                ]}
            >
                <Container>
                    <ToolbarRow>
                        <FormatToggle
                            type="button"
                            htmlActive={format === 'html'}
                            aria-pressed={format === 'html'}
                            onClick={() => {
                                const nextFormat = format === 'html' ? 'plaintext' : 'html';
                                setFieldValue('format', nextFormat);
                                setActiveTarget(nextFormat === 'html' ? 'html' : 'plaintext');
                            }}
                        >
                            <FormatToggleSide>
                                <Icon icon="file-alt" />
                                {t('text', 'Text', {}, 'Sitegeist.PaperTiger.CPX', 'Main')}
                            </FormatToggleSide>
                            <FormatToggleSide>
                                <Icon icon="code" />
                                {t('html', 'HTML', {}, 'Sitegeist.PaperTiger.CPX', 'Main')}
                            </FormatToggleSide>
                        </FormatToggle>
                        <AddressBar
                            entry={entry}
                            onSetFieldValue={setFieldValue}
                            onFocusSubject={() => setActiveTarget('subject')}
                            subjectInputRef={subjectInputRef}
                        />
                    </ToolbarRow>
                    {activeView === 'compatibility' && format === 'html' ? (
                        <EmailCompatibilityPane
                            htmlMarkup={html}
                            compatibility={compatibility}
                            clients={clients}
                            analysis={analysis}
                            onBackToEditor={() => setActiveView('editor')}
                        />
                    ) : (
                        <EditorLayout>
                            <PanelGroup direction="horizontal" autoSaveId={EDITOR_LAYOUT_STORAGE_KEY}>
                                <Panel defaultSize={50} minSize={20}>
                                    <EditorPane
                                        fieldTokens={fieldTokens}
                                        format={format}
                                        htmlValue={typeof entry.html === 'string' ? entry.html : ''}
                                        plaintextValue={typeof entry.plaintext === 'string' ? entry.plaintext : ''}
                                        onFocusHtml={handleHtmlFocus}
                                        onFocusPlaintext={handlePlaintextFocus}
                                        onHtmlChange={handleHtmlChange}
                                        onInsertToken={insertToken}
                                        onPlaintextChange={handlePlaintextChange}
                                        plaintextInputRef={plaintextInputRef}
                                        htmlEditorRef={htmlEditorRef}
                                    />
                                </Panel>
                                <PanelResizeHandle>
                                    <ResizeHandleVisual />
                                </PanelResizeHandle>
                                <Panel defaultSize={50} minSize={20}>
                                    <PreviewPane
                                        format={format}
                                        previewMarkup={previewMarkup}
                                        previewPlaintext={previewPlaintext}
                                        subject={subject}
                                        compatibility={compatibility}
                                        clients={clients}
                                        compatibilityScore={
                                            analysis.overview.totalCells > 0
                                                ? Math.round(analysis.overview.supportedPct)
                                                : null
                                        }
                                        onShowCompatibility={() => setActiveView('compatibility')}
                                    />
                                </Panel>
                            </PanelGroup>
                        </EditorLayout>
                    )}
                </Container>
            </Dialog>
        </>
    );
};
