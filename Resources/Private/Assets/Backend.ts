type ToggleMessageActionEditorEvent = {
    type: string;
    payload?: {
        visible: boolean;
        formId: string;
    };
};

window.addEventListener(
    'message',
    (event: MessageEvent<ToggleMessageActionEditorEvent>) => {
        if (event.origin !== window.location.origin) {
            return;
        }

        if (
            event.data?.type !==
            'Sitegeist.PaperTiger.CPX/TOGGLE_MESSAGE_ACTION_EDITOR'
        ) {
            return;
        }

        const {visible = false, formId} = event.data.payload ?? {};

        if (!formId) {
            return;
        }

        const form = document.getElementById(formId);

        const preview = document.querySelector<HTMLElement>(
            `[data-message-action-preview="${formId}"]`
        );

        if (!form || !preview) {
            console.warn('[Backend] Elements not found for formId:', formId);
            return;
        }

        form.hidden = visible;
        preview.hidden = !visible;
    }
);