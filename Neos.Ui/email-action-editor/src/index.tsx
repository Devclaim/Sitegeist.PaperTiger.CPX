import React from 'react';
import styled from 'styled-components';
import {Button, Icon} from '@neos-project/react-ui-components';
import {EmailActionDialogContainer} from './EmailActionDialog';
import {openEmailActionDialog} from './dialogState';

type GlobalRegistry = {
    get: (key: string) => any;
};

const Container = styled.div<{ highlight?: boolean }>`
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 12px;
    border: 1px solid #d7d7d7;
    border-radius: 6px;
    background: linear-gradient(180deg, #ffffff 0%, #f7f7f7 100%);

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
    gap: 8px;
`;

const ActionCard = styled.div`
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 12px;
    background: #fff;
    border: 1px solid #ececec;
    border-radius: 4px;
`;

const ActionRow = styled.div`
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    flex-wrap: wrap;
`;

const ActionTitle = styled.div`
    font-size: 12px;
    font-weight: 600;
`;

const ActionMeta = styled.pre`
    margin: 0;
    padding: 8px;
    overflow: auto;
    font-size: 11px;
    background: #fff;
    border: 1px solid #ececec;
    border-radius: 4px;
`;

const ActionButtons = styled.div`
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
`;

const createEmptyEmailAction = (): Record<string, unknown> => ({
    type: 'email',
    subject: null,
    format: 'plaintext',
    plaintext: null,
    html: null,
    recipientAddress: null
});

export function registerEmailActionEditor(globalRegistry: GlobalRegistry): void {
    const inspectorRegistry = globalRegistry.get('inspector');
    if (!inspectorRegistry) {
        console.warn('[Sitegeist.PaperTiger.CPX]: Could not find inspector registry.');
        console.warn('[Sitegeist.PaperTiger.CPX]: Skipping registration of EmailActionEditor...');
        return;
    }

    const editorsRegistry = inspectorRegistry.get('editors');
    if (!editorsRegistry) {
        console.warn('[Sitegeist.PaperTiger.CPX]: Could not find inspector editors registry.');
        console.warn('[Sitegeist.PaperTiger.CPX]: Skipping registration of EmailActionEditor...');
        return;
    }

    editorsRegistry.set('Sitegeist.PaperTiger.CPX/Inspector/Editors/EmailActionEditor', {
        component: (props: any) => {
            const value = Array.isArray(props.value) ? props.value : [];
            const commitEntries = (nextEntries: Record<string, unknown>[]): void => {
                props.commit(nextEntries);
            };

            const handleAdd = (): void => {
                commitEntries([
                    ...value,
                    createEmptyEmailAction()
                ]);
            };

            const handleDelete = (index: number): void => {
                commitEntries(value.filter((_: unknown, currentIndex: number) => currentIndex !== index));
            };

            const handleEdit = (index: number): void => {
                openEmailActionDialog({
                    index,
                    value: value[index] ?? createEmptyEmailAction(),
                    onApply: (editedIndex, nextValue) => {
                        commitEntries(value.map((entry: Record<string, unknown>, currentIndex: number) => (
                            currentIndex === editedIndex ? nextValue : entry
                        )));
                    }
                });
            };

            return (
                <Container highlight={props.highlight}>
                    <Title>Email Action Editor</Title>
                    <Description>
                        Add, remove and open individual email actions from here.
                    </Description>
                    <Button style="lighter" onClick={handleAdd}>
                        <Icon icon="envelope-o" padded="right" />
                        Add Email Action
                    </Button>
                    <ActionsList>
                        {value.map((entry: Record<string, unknown>, index: number) => (
                            <ActionCard key={index}>
                                <ActionRow>
                                    <ActionTitle>Email Action {index + 1}</ActionTitle>
                                    <ActionButtons>
                                        <Button style="lighter" onClick={() => handleEdit(index)}>
                                            Edit
                                        </Button>
                                        <Button style="lighter" onClick={() => handleDelete(index)}>
                                            Delete
                                        </Button>
                                    </ActionButtons>
                                </ActionRow>
                                <ActionMeta>{JSON.stringify(entry, null, 2)}</ActionMeta>
                            </ActionCard>
                        ))}
                    </ActionsList>
                </Container>
            );
        }
    });
}

export function registerEmailActionDialogContainer(globalRegistry: GlobalRegistry): void {
    const containersRegistry = globalRegistry.get('containers');
    if (!containersRegistry) {
        console.warn('[Sitegeist.PaperTiger.CPX]: Could not find containers registry.');
        console.warn('[Sitegeist.PaperTiger.CPX]: Skipping registration of EmailActionDialogContainer...');
        return;
    }

    containersRegistry.set(
        'Modals/Sitegeist.PaperTiger.CPX/EmailActionEditor',
        () => React.createElement(EmailActionDialogContainer)
    );
}
