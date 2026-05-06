import {put, select, takeLatest} from 'redux-saga/effects';
import {selectors} from '@neos-project/neos-ui-redux-store';

import type {IGlobalRegistry} from './GlobalRegistry';
import type {IStore} from './Store';

export const MESSAGE_ACTION_EDITOR_REDUCER_KEY =
    'Sitegeist.PaperTiger.CPX/messageActionEditor';

const MESSAGE_ACTION_EDITOR_PLUGIN_STATE_KEY =
    'Sitegeist.PaperTiger.CPX.messageActionEditor';

export const SET_MESSAGE_ACTION_EDITOR_VISIBILITY =
    'Sitegeist.PaperTiger.CPX/SET_MESSAGE_ACTION_EDITOR_VISIBILITY';

export const MESSAGE_ACTION_EDITOR_IFRAME_READY =
    'Sitegeist.PaperTiger.CPX/MESSAGE_ACTION_EDITOR_IFRAME_READY';

const MESSAGE_ACTION_EDITOR_SAGA_KEY =
    'Sitegeist.PaperTiger.CPX/watchMessageActionEditorStateSync';

type MessageActionEditorState = {
    visibleByNodeIdentifier: Record<string, boolean>;
    contextPathByNodeIdentifier: Record<string, string>;
};

type SetVisibilityAction = {
    type: typeof SET_MESSAGE_ACTION_EDITOR_VISIBILITY;
    payload: {
        nodeIdentifier: string;
        contextPath: string;
        visible: boolean;
    };
};

type IframeReadyAction = {
    type: typeof MESSAGE_ACTION_EDITOR_IFRAME_READY;
    payload?: {
        nodeIdentifiers?: string[];
    };
};

type MessageActionEditorHostEvent = {
    type?: string;
    payload?: {
        nodeIdentifiers?: string[];
    };
};

const initialState: MessageActionEditorState = {
    visibleByNodeIdentifier: {},
    contextPathByNodeIdentifier: {}
};

const getMessageActionEditorState = (state: any): MessageActionEditorState =>
    state?.plugins?.[MESSAGE_ACTION_EDITOR_PLUGIN_STATE_KEY] ?? initialState;

const isIframeReadyAction = (action: any): action is IframeReadyAction =>
    action?.type === MESSAGE_ACTION_EDITOR_IFRAME_READY;

export const setMessageActionEditorVisibility = (
    nodeIdentifier: string,
    contextPath: string,
    visible: boolean
): SetVisibilityAction => ({
    type: SET_MESSAGE_ACTION_EDITOR_VISIBILITY,
    payload: {
        nodeIdentifier,
        contextPath,
        visible
    }
});

export const messageActionEditorReducer = (
    state: any = {},
    action: any
): any => {
    switch (action.type) {
        case SET_MESSAGE_ACTION_EDITOR_VISIBILITY: {
            const {nodeIdentifier, contextPath, visible} = action.payload;
            const pluginState = getMessageActionEditorState(state);

            return {
                ...state,
                plugins: {
                    ...(state.plugins ?? {}),
                    [MESSAGE_ACTION_EDITOR_PLUGIN_STATE_KEY]: {
                        ...pluginState,
                        visibleByNodeIdentifier: {
                            ...(pluginState.visibleByNodeIdentifier ?? {}),
                            [nodeIdentifier]: visible
                        },
                        contextPathByNodeIdentifier: {
                            ...(pluginState.contextPathByNodeIdentifier ?? {}),
                            [nodeIdentifier]: contextPath
                        }
                    }
                }
            };
        }

        default:
            return state;
    }
};

export const selectMessageActionEditorVisible = (
    state: any,
    nodeIdentifier: string | null
): boolean => {
    if (!nodeIdentifier) {
        return false;
    }

    return (
        getMessageActionEditorState(state)
            .visibleByNodeIdentifier[nodeIdentifier] ?? false
    );
};

const syncVisibilityToIframe = (
    nodeIdentifier: string,
    visible: boolean
): void => {
    const iframe = document.querySelector<HTMLIFrameElement>(
        'iframe[name="neos-content-main"]'
    );

    if (!iframe?.contentWindow) {
        return;
    }

    iframe.contentWindow.postMessage(
        {
            type: SET_MESSAGE_ACTION_EDITOR_VISIBILITY,
            payload: {
                nodeIdentifier,
                visible
            }
        },
        window.location.origin
    );
};

const getFocusedNodeContextPath = (state: any): string | null =>
    selectors.CR.Nodes.focusedNodePathSelector(state) ?? null;

const getNodeByContextPath = (state: any, contextPath: string): any | null =>
    selectors.CR.Nodes.nodeByContextPath(state)(contextPath) ?? null;

const focusedNodeIsDirectChildOfOwner = (
    state: any,
    ownerContextPath: string,
    focusedNodeContextPath: string
): boolean => {
    const ownerNode = getNodeByContextPath(state, ownerContextPath);

    return (
        ownerNode?.children?.some(
            (child: any) => child?.contextPath === focusedNodeContextPath
        ) ?? false
    );
};

export function* watchMessageActionEditorStateSync(): Generator<any, void, any> {
    yield takeLatest(
        '*',
        function* (action: any): Generator<any, void, any> {
            const state = yield select();
            const pluginState = getMessageActionEditorState(state);
            const focusedNodeContextPath = getFocusedNodeContextPath(state);

            if (
                action.type !== SET_MESSAGE_ACTION_EDITOR_VISIBILITY &&
                focusedNodeContextPath
            ) {
                for (const [nodeIdentifier, visible] of Object.entries(
                    pluginState.visibleByNodeIdentifier ?? {}
                )) {
                    if (!visible) {
                        continue;
                    }

                    const ownerContextPath =
                        pluginState.contextPathByNodeIdentifier?.[
                            nodeIdentifier
                        ];

                    if (!ownerContextPath) {
                        continue;
                    }

                    if (
                        focusedNodeIsDirectChildOfOwner(
                            state,
                            ownerContextPath,
                            focusedNodeContextPath
                        )
                    ) {
                        yield put(
                            setMessageActionEditorVisibility(
                                nodeIdentifier,
                                ownerContextPath,
                                false
                            )
                        );

                        return;
                    }
                }
            }

            if (action.type === MESSAGE_ACTION_EDITOR_IFRAME_READY) {
                const requestedNodeIdentifiers =
                    action.payload?.nodeIdentifiers ?? [];

                if (requestedNodeIdentifiers.length > 0) {
                    requestedNodeIdentifiers.forEach((nodeIdentifier: string) => {
                        syncVisibilityToIframe(
                            nodeIdentifier,
                            pluginState.visibleByNodeIdentifier?.[nodeIdentifier] ?? false
                        );
                    });
                    return;
                }

                Object.entries(
                    pluginState.visibleByNodeIdentifier ?? {}
                ).forEach(([nodeIdentifier, visible]) => {
                    syncVisibilityToIframe(nodeIdentifier, visible);
                });

                return;
            }

            if (action.type === SET_MESSAGE_ACTION_EDITOR_VISIBILITY) {
                syncVisibilityToIframe(
                    action.payload.nodeIdentifier,
                    action.payload.visible
                );
            }
        }
    );
}

export const registerMessageActionEditorStore = (
    globalRegistry: IGlobalRegistry,
    store?: IStore
): void => {
    globalRegistry.get('reducers')?.set(MESSAGE_ACTION_EDITOR_REDUCER_KEY, {
        reducer: messageActionEditorReducer
    });

    globalRegistry.get('sagas')?.set(MESSAGE_ACTION_EDITOR_SAGA_KEY, {
        saga: watchMessageActionEditorStateSync
    });

    if (!store || typeof window === 'undefined') {
        return;
    }

    window.addEventListener(
        'message',
        (event: MessageEvent<MessageActionEditorHostEvent>) => {
            if (event.origin !== window.location.origin) {
                return;
            }

            if (event.data?.type !== MESSAGE_ACTION_EDITOR_IFRAME_READY) {
                return;
            }

            store.dispatch({
                type: MESSAGE_ACTION_EDITOR_IFRAME_READY,
                payload: {
                    nodeIdentifiers: event.data.payload?.nodeIdentifiers ?? []
                }
            });
        }
    );
};
