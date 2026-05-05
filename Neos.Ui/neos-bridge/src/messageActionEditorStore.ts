import {select, takeLatest} from 'redux-saga/effects';
import type {IGlobalRegistry} from './GlobalRegistry';

export const MESSAGE_ACTION_EDITOR_REDUCER_KEY =
    'Sitegeist.PaperTiger.CPX/messageActionEditor';
const MESSAGE_ACTION_EDITOR_PLUGIN_STATE_KEY =
    'Sitegeist.PaperTiger.CPX.messageActionEditor';

export const TOGGLE_MESSAGE_ACTION_EDITOR =
    'Sitegeist.PaperTiger.CPX/TOGGLE_MESSAGE_ACTION_EDITOR';

const MESSAGE_ACTION_EDITOR_SAGA_KEY =
    'Sitegeist.PaperTiger.CPX/watchMessageActionEditorToggle';

type MessageActionEditorState = {
    visibleByFormId: Record<string, boolean>;
};

type ToggleAction = {
    type: typeof TOGGLE_MESSAGE_ACTION_EDITOR;
    payload: {
        formId: string;
    };
};

const initialState: MessageActionEditorState = {
    visibleByFormId: {}
};

const getMessageActionEditorState = (state: any): MessageActionEditorState =>
    state?.plugins?.[MESSAGE_ACTION_EDITOR_PLUGIN_STATE_KEY] ?? initialState;

export const toggleMessageActionEditor = (formId: string): ToggleAction => ({
    type: TOGGLE_MESSAGE_ACTION_EDITOR,
    payload: {formId}
});

export const messageActionEditorReducer = (
    state: any = {},
    action: ToggleAction
): any => {
    switch (action.type) {
        case TOGGLE_MESSAGE_ACTION_EDITOR: {
            const {formId} = action.payload;
            const pluginState = getMessageActionEditorState(state);
            const visibleByFormId = pluginState.visibleByFormId ?? {};

            return {
                ...state,
                plugins: {
                    ...(state.plugins ?? {}),
                    [MESSAGE_ACTION_EDITOR_PLUGIN_STATE_KEY]: {
                        ...pluginState,
                        visibleByFormId: {
                            ...visibleByFormId,
                            [formId]: !visibleByFormId[formId]
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
    formId: string | null
): boolean => {
    if (!formId) {
        return false;
    }

    return getMessageActionEditorState(state).visibleByFormId[formId] ?? false;
};

export function* watchMessageActionEditorToggle(): Generator<any, void, any> {
    yield takeLatest(
        TOGGLE_MESSAGE_ACTION_EDITOR,
        function* (action: ToggleAction): Generator<any, void, any> {
            const {formId} = action.payload;
            const state = yield select();

            const visible =
                getMessageActionEditorState(state).visibleByFormId[formId] ?? false;

            const iframe = document.querySelector<HTMLIFrameElement>(
                'iframe[name="neos-content-main"]'
            );

            iframe?.contentWindow?.postMessage(
                {
                    type: TOGGLE_MESSAGE_ACTION_EDITOR,
                    payload: {formId, visible}
                },
                '*'
            );
        }
    );
}

export const registerMessageActionEditorStore = (
    globalRegistry: IGlobalRegistry
): void => {
    globalRegistry.get('reducers')?.set(MESSAGE_ACTION_EDITOR_REDUCER_KEY, {
        reducer: messageActionEditorReducer
    });

    globalRegistry.get('sagas')?.set(MESSAGE_ACTION_EDITOR_SAGA_KEY, {
        saga: watchMessageActionEditorToggle
    });
};
