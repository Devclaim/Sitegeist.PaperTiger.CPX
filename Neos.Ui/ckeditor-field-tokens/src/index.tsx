import {FieldTokenPlugin} from './FieldTokenPlugin';
import {
    resolveFieldTokenOptions
} from '@sitegeist/papertiger-cpx-neos-bridge';

type GlobalRegistry = {
    get: (key: string) => {
        get: <T>(key: string) => T;
    } | undefined;
};

type Store = {
    getState(): any;
};

type I18nRegistry = {
    translate: (
        id: string,
        fallback?: string,
        params?: Record<string, unknown>,
        packageKey?: string,
        sourceName?: string,
        quantity?: number
    ) => string;
};

const isFieldTokenFeatureEnabled = (editorOptions: any): boolean =>
    editorOptions?.paperTigerFieldTokens?.enabled === true;

const resolveEditorContextPath = (state: any, options: any): string | null => {
    const propertyDomNode = options?.propertyDomNode;
    const contextPathFromPropertyDomNode =
        typeof propertyDomNode?.getAttribute === 'function' ?
            propertyDomNode.getAttribute('data-__neos-editable-node-contextpath') :
            null;

    if (
        typeof contextPathFromPropertyDomNode === 'string' &&
        contextPathFromPropertyDomNode !== ''
    ) {
        return contextPathFromPropertyDomNode;
    }

    console.warn(
        '[Sitegeist.PaperTiger.CPX] FieldTokenPlugin: Missing data-__neos-editable-node-contextpath on propertyDomNode.',
        {
            propertyDomNode,
            options,
            state
        }
    );

    return null;
};

const appendToolbarItems = (
    toolbarConfiguration: any,
    itemsToAppend: string[]
): any => {
    if (Array.isArray(toolbarConfiguration)) {
        return [
            ...toolbarConfiguration,
            ...itemsToAppend
        ];
    }

    if (toolbarConfiguration && Array.isArray(toolbarConfiguration.items)) {
        return {
            ...toolbarConfiguration,
            items: [
                ...toolbarConfiguration.items,
                ...itemsToAppend
            ]
        };
    }

    return {
        items: itemsToAppend,
        shouldNotGroupWhenFull: true
    };
};

export function registerFieldTokenCkEditorIntegration(
    globalRegistry: GlobalRegistry,
    store: Store
): void {
    const ckEditorRegistry = globalRegistry.get('ckEditor5');
    const configRegistry = ckEditorRegistry?.get<any>('config');
    const i18nRegistry = globalRegistry.get('i18n') as I18nRegistry | undefined;

    if (!configRegistry) {
        console.warn(
            '[Sitegeist.PaperTiger.CPX]: Could not find ckEditor5 config registry. Skipping field token integration.'
        );
        return;
    }

    configRegistry.set(
        'Sitegeist.PaperTiger.CPX/FieldTokenPlugin',
        (ckEditorConfiguration: any, options: any) => {
            if (!isFieldTokenFeatureEnabled(options.editorOptions)) {
                return ckEditorConfiguration;
            }

            const state = store.getState();
            const contextPath = resolveEditorContextPath(state, options);
            const tokens = resolveFieldTokenOptions(state, contextPath);
            const label =
                i18nRegistry?.translate(
                    'fields',
                    'Fields',
                    {},
                    'Sitegeist.PaperTiger.CPX',
                    'Main'
                ) ?? 'Fields';

            return {
                ...ckEditorConfiguration,
                plugins: [
                    ...(ckEditorConfiguration.plugins ?? []),
                    FieldTokenPlugin
                ],
                paperTigerFieldTokens: {
                    ...(ckEditorConfiguration.paperTigerFieldTokens ?? {}),
                    label,
                    tokens
                },
                toolbar: appendToolbarItems(
                    ckEditorConfiguration.toolbar,
                    [
                        'paperTigerFieldTokenDropdown'
                    ]
                )
            };
        }
    );
}
