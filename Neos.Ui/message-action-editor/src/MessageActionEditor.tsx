import React from 'react';
import {useDispatch, useSelector} from 'react-redux';
import styled from 'styled-components';
import {Icon} from '@neos-project/react-ui-components';

import {
    resolveFocusedNodeState,
    selectMessageActionEditorVisible,
    setMessageActionEditorVisibility,
    useI18n
} from '@sitegeist/papertiger-cpx-neos-bridge';

type Props = {
    highlight?: boolean;
    openHtmlEditor?: () => void;
};

const ToggleButton = styled.button<{ visible: boolean }>`
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 6px;
    margin-top: 16px !important;
    border: 0;
    background: #3f3f3f;
    color: white;
    cursor: pointer;
    overflow: hidden;
    transition: background-color 180ms ease, opacity 180ms ease;

    &:disabled {
        cursor: not-allowed;
        opacity: 0.55;
    }

    &::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 50%;
        background: #00adee;
        box-shadow: 0 8px 20px rgba(11, 111, 219, 0.28);
        transform: translateX(${({visible}) => visible ? '100%' : '0%'});
        transition: transform 220ms ease;
    }
`;

const ToggleSide = styled.span<{ active: boolean }>`
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-width: 0;
    flex: 1;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 700;
    color: #ffffff;
    transition: color 180ms ease;
`;

const useFocusedNodeState = () =>
    useSelector((state: any) => resolveFocusedNodeState(state));

export const MessageActionEditor: React.FC<Props> = () => {
    const t = useI18n();
    const dispatch = useDispatch();

    const {nodeIdentifier, contextPath} = useFocusedNodeState();

    const visible = useSelector((state: any) =>
        selectMessageActionEditorVisible(state, nodeIdentifier)
    );

    const handleClick = (): void => {
        if (!nodeIdentifier || !contextPath) {
            return;
        }

        dispatch(
            setMessageActionEditorVisibility(
                nodeIdentifier,
                contextPath,
                !visible
            )
        );
    };

    return (
        <ToggleButton
            onClick={handleClick}
            disabled={!nodeIdentifier}
            visible={visible}
            aria-pressed={visible}
        >
            <ToggleSide active={!visible}>
                <Icon icon="file-alt" />
                {t('Sitegeist.PaperTiger.CPX:Main:formView')}
            </ToggleSide>
            <ToggleSide active={visible}>
                <Icon icon="commenting-o" />
                {t('Sitegeist.PaperTiger.CPX:Main:messageView')}
            </ToggleSide>
        </ToggleButton>
    );
};
