import React from 'react';
import styled from 'styled-components';

import {Button, Dialog} from '@neos-project/react-ui-components';

import {closeEmailActionDialog, useEmailActionDialogState} from './dialogState';

const Container = styled.div`
    display: flex;
    flex-direction: column;
    gap: 16px;
    min-height: 50vh;
`;

const Header = styled.div`
    display: flex;
    flex-direction: column;
    gap: 6px;
`;

const Title = styled.h2`
    margin: 0;
    font-size: 18px;
    line-height: 1.2;
`;

const Description = styled.p`
    margin: 0;
    color: #666;
    font-size: 13px;
`;

const Placeholder = styled.div`
    padding: 16px;
    border: 1px solid #d7d7d7;
    border-radius: 6px;
    background: linear-gradient(180deg, #ffffff 0%, #f7f7f7 100%);
    color: #444;
    min-height: 240px;
`;

const Code = styled.pre`
    margin: 0;
    padding: 12px;
    border-radius: 4px;
    background: #fff;
    border: 1px solid #ececec;
    overflow: auto;
    font-size: 11px;
`;

export const EmailActionDialogContainer: React.FC = () => {
    const {isOpen, payload} = useEmailActionDialogState();
    const [entry, setEntry] = React.useState<Record<string, unknown>>({});

    React.useEffect(() => {
        if (isOpen) {
            setEntry(payload?.value ?? {});
        }
    }, [isOpen, payload]);

    if (!isOpen || !payload) {
        return null;
    }

    const handleApply = (): void => {
        payload.onApply(payload.index, entry);
        closeEmailActionDialog();
    };

    return (
        <Dialog
            isOpen
            title="Email Editor"
            onRequestClose={closeEmailActionDialog}
            actions={[
                <Button type="button" onClick={closeEmailActionDialog}>
                    Close
                </Button>,
                <Button style="success" type="button" onClick={handleApply}>
                    Apply
                </Button>
            ]}
        >
            <Container>
                <Header>
                    <Title>Email Action Editor</Title>
                    <Description>
                        Placeholder for the future email editor for one email action at a time.
                    </Description>
                </Header>
                <Placeholder>
                    This dialog is mounted through the Neos container registry and is scoped to a single email action entry.
                </Placeholder>
                <Code>{JSON.stringify(entry, null, 2)}</Code>
            </Container>
        </Dialog>
    );
};
