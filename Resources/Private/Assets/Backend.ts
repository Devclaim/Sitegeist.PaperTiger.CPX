type ToggleMessageActionEditorEvent = {
    type: string;
    payload?: {
        visible: boolean;
        nodeIdentifier: string;
        nodeIdentifiers?: string[];
    };
};

const MESSAGE_ACTION_EDITOR_VISIBILITY_KEY =
    'Sitegeist.PaperTiger.CPX/SET_MESSAGE_ACTION_EDITOR_VISIBILITY';

const MESSAGE_ACTION_EDITOR_IFRAME_READY_KEY =
    'Sitegeist.PaperTiger.CPX/MESSAGE_ACTION_EDITOR_IFRAME_READY';

const applyVisibility = (
    nodeIdentifier: string,
    visible: boolean
): void => {
    const form = document.getElementById(`form_${nodeIdentifier}`);

    const preview = document.querySelector<HTMLElement>(
        `[data-message-action-preview="form_${nodeIdentifier}"]`
    );

    if (!form || !preview) {
        return;
    }

    form.hidden = visible;
    preview.hidden = !visible;
};

const getRenderedNodeIdentifiers = (): string[] =>
    Array.from(
        document.querySelectorAll<HTMLElement>('[data-message-action-preview]')
    )
        .map((element) => element.dataset.messageActionPreview ?? null)
        .filter((formId): formId is string => typeof formId === 'string' && formId.startsWith('form_'))
        .map((formId) => formId.slice('form_'.length));

const initializeMessageActionEditorState = (): void => {
    window.parent.postMessage(
        {
            type: MESSAGE_ACTION_EDITOR_IFRAME_READY_KEY,
            payload: {
                nodeIdentifiers: getRenderedNodeIdentifiers()
            }
        },
        window.location.origin
    );
};

if (document.readyState === 'loading') {
    document.addEventListener(
        'DOMContentLoaded',
        initializeMessageActionEditorState,
        {once: true}
    );
} else {
    initializeMessageActionEditorState();
}

window.addEventListener(
    'message',
    (event: MessageEvent<ToggleMessageActionEditorEvent>) => {
        if (event.origin !== window.location.origin) {
            return;
        }

        if (event.data?.type !== MESSAGE_ACTION_EDITOR_VISIBILITY_KEY) {
            return;
        }

        const {visible = false, nodeIdentifier} =
            event.data.payload ?? {};

        if (!nodeIdentifier) {
            return;
        }

        applyVisibility(nodeIdentifier, visible);
    }
);
