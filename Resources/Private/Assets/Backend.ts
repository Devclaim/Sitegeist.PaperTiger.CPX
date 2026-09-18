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

/**
 * Scripts that ship with a field's own markup (currently Scripts/Altcha.js, rendered by
 * AltchaFormFieldRenderer) are inert when a node is created in the backend: Neos inserts the
 * freshly rendered HTML into the guest frame through the DOM, and <script src> elements added
 * that way are never executed. The field therefore stays dead until a full page reload.
 *
 * Re-insert such scripts once per source when Neos reports a new node, mirroring the
 * "Neos.NodeCreated" remount used in Vendor.Shared/Resources/Private/Root.ts.
 */
const executedScriptSources = new Set<string>();

const absoluteScriptSource = (script: HTMLScriptElement): string | null => {
    const src = script.getAttribute('src');
    if (!src) {
        return null;
    }

    try {
        return new URL(src, document.baseURI).href;
    } catch {
        return null;
    }
};

const rememberScriptsAlreadyInDocument = (): void => {
    document
        .querySelectorAll<HTMLScriptElement>('script[src]')
        .forEach((script) => {
            const source = absoluteScriptSource(script);
            if (source !== null) {
                executedScriptSources.add(source);
            }
        });
};

const executePendingScripts = (root: Element | Document): void => {
    const scripts: HTMLScriptElement[] = [];

    if (root instanceof HTMLScriptElement) {
        scripts.push(root);
    }
    scripts.push(...Array.from(root.querySelectorAll<HTMLScriptElement>('script[src]')));

    for (const original of scripts) {
        const source = absoluteScriptSource(original);
        if (source === null || executedScriptSources.has(source)) {
            continue;
        }

        executedScriptSources.add(source);

        const script = document.createElement('script');
        script.src = original.getAttribute('src') as string;
        script.defer = true;
        document.body.appendChild(script);
    }
};

const initializeNodeCreationScriptHandling = (): void => {
    rememberScriptsAlreadyInDocument();

    document.addEventListener('Neos.NodeCreated', (event: Event) => {
        const element = (event as CustomEvent<{ element?: HTMLElement }>).detail?.element;
        executePendingScripts(element ?? document);
    });
};

if (document.readyState === 'loading') {
    document.addEventListener(
        'DOMContentLoaded',
        initializeNodeCreationScriptHandling,
        {once: true}
    );
} else {
    initializeNodeCreationScriptHandling();
}
