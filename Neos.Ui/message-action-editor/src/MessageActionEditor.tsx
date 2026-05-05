import React from 'react';
import {useDispatch, useSelector} from 'react-redux';
import {selectors} from '@neos-project/neos-ui-redux-store';
import {Button, Icon} from '@neos-project/react-ui-components';

import {
    selectMessageActionEditorVisible,
    toggleMessageActionEditor,
    useI18n
} from '@sitegeist/papertiger-cpx-neos-bridge';

type Props = {
    highlight?: boolean;
    openHtmlEditor?: () => void;
};

const useFocusedFormId = (): string | null => {
    return useSelector((state: any) => {
        const focusedNodeContextPath =
            selectors.CR.Nodes.focusedNodePathSelector(state);

        const getNodeByContextPath =
            selectors.CR.Nodes.nodeByContextPath(state);

        const focusedNode = focusedNodeContextPath ?
            getNodeByContextPath(focusedNodeContextPath) :
            null;

        return focusedNode?.identifier ?
            `form_${focusedNode.identifier}` :
            null;
    });
};

export const MessageActionEditor: React.FC<Props> = () => {
    const t = useI18n();
    const dispatch = useDispatch();
    const formId = useFocusedFormId();

    const visible = useSelector((state: any) =>
        selectMessageActionEditorVisible(state, formId)
    );

    const handleClick = (): void => {
        console.log('[Sitegeist.PaperTiger.CPX]: MessageActionEditor clicked', {
            formId,
            visible
        });

        if (!formId) {
            console.warn(
                '[Sitegeist.PaperTiger.CPX]: Could not determine focused form id.'
            );
            return;
        }

        dispatch(toggleMessageActionEditor(formId));
    };

    return (
        <Button onClick={handleClick}>
            <Icon icon={visible ? 'eye-slash' : 'eye'} padded="right" />
            {visible ?
                t('Sitegeist.PaperTiger.CPX:Main:hideMessageEditor') :
                t('Sitegeist.PaperTiger.CPX:Main:showMessageEditor')
            }
        </Button>
    );
};