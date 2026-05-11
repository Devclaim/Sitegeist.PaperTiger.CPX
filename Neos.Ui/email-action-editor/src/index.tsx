import React from 'react';
import styled from 'styled-components';
import {Icon} from '@neos-project/react-ui-components';
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

const Container = styled.div<{ highlight?: boolean }>`
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 0;
    background: transparent;

    ${({highlight}) => highlight && `
        box-shadow: 0 0 0 2px #ff8700;
        border-radius: 2px;
    `}
`;

const Title = styled.div`
    font-weight: 600;
`;

const Description = styled.div`
    font-size: 12px;
    color: #666;
`;

const ActionsList = styled.div`
    display: flex;
    flex-direction: column;
    gap: 10px;
`;

const ActionRow = styled.div`
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    flex-wrap: wrap;
    padding: 0 2px;
`;

const ActionSummary = styled.div`
    display: flex;
    align-items: baseline;
    gap: 8px;
    min-width: 0;
    font-weight: 600;
`;

const ActionLabel = styled.span`
    flex: 0 0 auto;
    font-size: 12px;
    color: #8a8a8a;
    text-transform: uppercase;
    letter-spacing: 0.04em;
`;

const ActionValue = styled.span`
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 13px;
    color: #1f1f1f;
`;

const ActionSeparator = styled.span`
    color: #b5b5b5;
`;

const IconButton = styled.button`
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    padding: 0;
    border: 0;
    background: transparent;
    color: #5f5f5f;
    cursor: pointer;
    transition: color 140ms ease, background-color 140ms ease;

    &:hover {
        color: #26224c;
        background: rgba(38, 34, 76, 0.08);
    }
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
    color: #3c3c3c;
    cursor: pointer;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.02em;
    border-radius: 4px;

    &:hover {
        border-color: #26224c;
        color: #26224c;
        background: rgba(38, 34, 76, 0.04);
    }
`;

const ActionButtons = styled.div`
    display: flex;
    align-items: center;
    gap: 2px;
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

const EmailActionEditorComponent: React.FC<any> = (props) => {
    const t = useI18n();
    const value = Array.isArray(props.value) ? props.value : [];
    const focusedContextPath = useNeosSelector(resolveFocusedNodeContextPath);
    const fieldTokens = useNeosSelector((state) =>
        resolveFieldTokenOptions(state, focusedContextPath)
    );
    const commitEntries = (nextEntries: Record<string, unknown>[]): void => {
        props.commit(nextEntries);
    };

    const handleAdd = (): void => {
        const nextEntries = [...value, createEmptyEmailAction()];
        const nextIndex = nextEntries.length - 1;

        commitEntries(nextEntries);

        openEmailActionDialog({
            index: nextIndex,
            value: nextEntries[nextIndex],
            fieldTokens,
            onApply: (editedIndex, nextValue) => {
                props.commit(
                    nextEntries.map((entry: Record<string, unknown>, currentIndex: number) => (
                        currentIndex === editedIndex ? nextValue : entry
                    ))
                );
            }
        });
    };

    const handleDelete = (index: number): void => {
        commitEntries(value.filter((_: unknown, currentIndex: number) => currentIndex !== index));
    };

    const handleEdit = (index: number): void => {
        openEmailActionDialog({
            index,
            value: value[index] ?? createEmptyEmailAction(),
            fieldTokens,
            onApply: (editedIndex, nextValue) => {
                commitEntries(value.map((entry: Record<string, unknown>, currentIndex: number) => (
                    currentIndex === editedIndex ? nextValue : entry
                )));
            }
        });
    };

    return (
        <Container highlight={props.highlight}>
            <Title>{t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.title')}</Title>
            <Description>
                {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.description')}
            </Description>
            <ActionsList>
                {value.map((entry: Record<string, unknown>, index: number) => (
                    <ActionRow key={index}>
                        <ActionSummary>
                            <ActionLabel>{t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.emailLabel')} {index + 1}</ActionLabel>
                            <ActionValue>
                                {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.recipient')}: {formatInlineValue(entry.recipientAddress, t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.notSet'))}
                            </ActionValue>
                            <ActionSeparator>/</ActionSeparator>
                            <ActionValue>
                                {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.sender')}: {formatInlineValue(entry.senderAddress, t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.notSet'))}
                            </ActionValue>
                        </ActionSummary>
                        <ActionButtons>
                            <IconButton type="button" onClick={() => handleEdit(index)} aria-label={`${t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.edit')} ${t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.emailLabel')} ${index + 1}`}>
                                <Icon icon="pencil" />
                            </IconButton>
                            <IconButton type="button" onClick={() => handleDelete(index)} aria-label={`${t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.delete')} ${t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.emailLabel')} ${index + 1}`}>
                                <Icon icon="trash" />
                            </IconButton>
                        </ActionButtons>
                    </ActionRow>
                ))}
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
