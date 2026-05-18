import React from 'react';
import styled from 'styled-components';
import {Button, Icon} from '@neos-project/react-ui-components';
import {
    IGlobalRegistry,
    IStore,
    NeosContext,
    Registry,
    resolveFieldTokenOptions,
    resolveFocusedNodeContextPath,
    useI18n,
    useNeosSelector
} from '@sitegeist/papertiger-cpx-neos-bridge';
import {EmailActionDialogContainer} from './EmailActionDialog';
import {openEmailActionDialog} from './dialogState';

const Container = styled.div`
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: transparent;
`;

const ActionsList = styled.div`
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 12px;
`;

const ActionRow = styled.div<{ dirty?: boolean }>`
    background-color: var(--colors-ContrastDarkest);
    padding: 8px 12px;
    margin-bottom: 1px;
    line-height: 20px;
    min-height: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    ${({dirty}) => dirty && `
        box-shadow: 0 0 0 2px #ff8700;
    `}
`;

const ActionSummary = styled.div`
    display: flex;
    flex-direction: column;
    align-items: baseline;
    gap: 2px;
`;

const ActionLabel = styled.span<{ changed?: boolean }>`
    flex: 0 0 auto;
    font-size: 14px;
    font-weight: bold;
    color: ${({changed}) => (changed ? '#ffb24d' : '#fff')};
    letter-spacing: 0.04em;
`;

const ActionValue = styled.span<{ changed?: boolean }>`
    min-width: 0;
    font-size: 14px;
    color: ${({changed}) => (changed ? '#ffd9a3' : '#999')};
`;

const AddButton = styled.button`
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px 14px;
    border: 1px dashed #b5b5b5;
    background: transparent;
    color: #b5b5b5;
    cursor: pointer;
    font-size: 11px;

    &:hover {
        border-color: #00adee;
        color: #00adee;
    }

    svg {
        color: inherit;
    }
`;

const ActionButtons = styled.div`
    display: flex;
    align-items: center;
    gap: 4px;
    width: 100%;

    .btn--action {
        width: 100%;
        font-size: 12px;
    }
    
    .btn--action svg {
        margin-right: 8px;
    }
`;

const createEmptyEmailAction = (): Record<string, unknown> => ({
    type: 'email',
    subject: null,
    format: 'plaintext',
    plaintext: null,
    html: null,
    recipientAddress: null,
    senderAddress: null
});

const formatInlineValue = (value: unknown, fallback: string): string => {
    if (typeof value !== 'string') {
        return fallback;
    }

    const trimmed = value.trim();
    return trimmed.length > 0 ? trimmed : fallback;
};

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

const buildFieldHighlights = (
    baseline: Record<string, unknown>,
    current: Record<string, unknown>
): Record<string, boolean> => ({
    subject: baseline.subject !== current.subject,
    senderAddress: baseline.senderAddress !== current.senderAddress,
    recipientAddress: baseline.recipientAddress !== current.recipientAddress,
    senderName: baseline.senderName !== current.senderName,
    recipientName: baseline.recipientName !== current.recipientName,
    replyToAddress: baseline.replyToAddress !== current.replyToAddress,
    carbonCopyAddress: baseline.carbonCopyAddress !== current.carbonCopyAddress,
    blindCarbonCopyAddress: baseline.blindCarbonCopyAddress !== current.blindCarbonCopyAddress,
    format: baseline.format !== current.format,
    html: baseline.html !== current.html,
    plaintext: baseline.plaintext !== current.plaintext
});

const EmailActionEditorComponent: React.FC<any> = (props) => {
    const t = useI18n();
    const value = Array.isArray(props.value) ? props.value : [];
    const focusedContextPath = useNeosSelector(resolveFocusedNodeContextPath);
    const fieldTokens = useNeosSelector((state) =>
        resolveFieldTokenOptions(state, focusedContextPath)
    );
    const lastCleanValueRef = React.useRef<Record<string, unknown>[]>(value);

    React.useEffect(() => {
        if (props.highlight === false) {
            lastCleanValueRef.current = value;
        }
    }, [props.highlight, value]);

    const commitEntries = (nextEntries: Record<string, unknown>[]): void => {
        props.commit(nextEntries);
    };

    const handleAdd = (): void => {
        const emptyEntry = createEmptyEmailAction();

        openEmailActionDialog({
            index: value.length,
            baselineValue: emptyEntry,
            value: emptyEntry,
            neosHighlight: false,
            neosFieldHighlights: {},
            fieldTokens,
            onApply: (_editedIndex, nextValue) => {
                const updatedEntries = [...value, nextValue];
                commitEntries(updatedEntries);
            }
        });
    };

    const handleDelete = (index: number): void => {
        commitEntries(value.filter((_: unknown, currentIndex: number) => currentIndex !== index));
    };

    const handleEdit = (index: number): void => {
        const currentValue = value[index] ?? createEmptyEmailAction();
        const baselineValue = value[index] ?? createEmptyEmailAction();
        const committedBaseline = lastCleanValueRef.current[index] ?? {};
        openEmailActionDialog({
            index,
            baselineValue,
            value: currentValue,
            neosHighlight: props.highlight === true,
            neosFieldHighlights: props.highlight === true
                ? buildFieldHighlights(committedBaseline, currentValue)
                : {},
            fieldTokens,
            onApply: (editedIndex, nextValue) => {
                const updatedEntries = value.map((entry: Record<string, unknown>, currentIndex: number) => (
                    currentIndex === editedIndex ? nextValue : entry
                ));
                commitEntries(updatedEntries);
            }
        });
    };

    return (
        <Container>
            <ActionsList>
                {value.map((entry: Record<string, unknown>, index: number) => {
                    const baselineEntry = lastCleanValueRef.current[index] ?? {};
                    const isRowChanged = props.highlight === true && !areEmailEntriesEqual(baselineEntry, entry);
                    const isSubjectChanged = props.highlight === true && baselineEntry.subject !== entry.subject;
                    const isSenderChanged = props.highlight === true && baselineEntry.senderAddress !== entry.senderAddress;
                    const isRecipientChanged = props.highlight === true && baselineEntry.recipientAddress !== entry.recipientAddress;
                    return (
                    <ActionRow key={index} dirty={isRowChanged}>
                        <ActionSummary>
                            <ActionLabel changed={isSubjectChanged}>
                                {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.subject')}
                            </ActionLabel>
                            <ActionValue changed={isSubjectChanged}>
                                {formatInlineValue(entry.subject, t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.notSet'))}
                            </ActionValue>
                        </ActionSummary>
                        <ActionSummary>
                            <ActionLabel changed={isSenderChanged}>{t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.sender')}</ActionLabel>
                            <ActionValue changed={isSenderChanged}>
                                {formatInlineValue(entry.senderAddress, t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.notSet'))}
                            </ActionValue>
                        </ActionSummary>
                        <ActionSummary>
                           <ActionLabel changed={isRecipientChanged}>{t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.recipient')}</ActionLabel>
                            <ActionValue changed={isRecipientChanged}>
                                {formatInlineValue(entry.recipientAddress, t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.notSet'))}
                            </ActionValue>
                        </ActionSummary>
                        <ActionButtons>
                            <Button className='btn--action' onClick={() => handleEdit(index)} aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.edit')}>
                                <Icon icon="pencil" />
                                {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.edit')}
                            </Button>
                            <Button className='btn--action' style='error' hoverStyle='error' onClick={() => handleDelete(index)} aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.delete')}>
                                <Icon icon="trash" />
                                {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.delete')}
                            </Button>
                        </ActionButtons>
                    </ActionRow>
                )})}
            </ActionsList>
            <AddButton type="button" onClick={handleAdd}>
                <Icon icon="plus" />
                {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.addEmail')}
            </AddButton>
        </Container>
    );
};

export function registerEmailActionEditor(globalRegistry: IGlobalRegistry, store: IStore): void {
    const inspectorRegistry = globalRegistry.get('inspector');
    if (!inspectorRegistry) {
        console.warn('[Sitegeist.PaperTiger.CPX]: Could not find inspector registry.');
        console.warn('[Sitegeist.PaperTiger.CPX]: Skipping registration of EmailActionEditor...');
        return;
    }

    const editorsRegistry = inspectorRegistry.get('editors') as Registry;
    if (!editorsRegistry) {
        console.warn('[Sitegeist.PaperTiger.CPX]: Could not find inspector editors registry.');
        console.warn('[Sitegeist.PaperTiger.CPX]: Skipping registration of EmailActionEditor...');
        return;
    }

    editorsRegistry.set('Sitegeist.PaperTiger.CPX/Inspector/Editors/EmailActionEditor', {
        component: (props: any) =>
            React.createElement(
                NeosContext.Provider,
                {value: {globalRegistry, store}},
                React.createElement(EmailActionEditorComponent, props)
            )
    });
}

export function registerEmailActionDialogContainer(globalRegistry: IGlobalRegistry, store: IStore): void {
    const containersRegistry = globalRegistry.get('containers');
    if (!containersRegistry) {
        console.warn('[Sitegeist.PaperTiger.CPX]: Could not find containers registry.');
        console.warn('[Sitegeist.PaperTiger.CPX]: Skipping registration of EmailActionDialogContainer...');
        return;
    }

    containersRegistry.set(
        'Modals/Sitegeist.PaperTiger.CPX/EmailActionEditor',
        () =>
            React.createElement(
                NeosContext.Provider,
                {value: {globalRegistry, store}},
                React.createElement(EmailActionDialogContainer)
            )
    );
}
