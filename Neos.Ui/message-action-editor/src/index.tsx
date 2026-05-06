import React from 'react';

import {MessageActionEditor} from './MessageActionEditor';

import {
    NeosContext,
    IGlobalRegistry,
    IStore,
    Registry
} from '@sitegeist/papertiger-cpx-neos-bridge';

export function registerMessageActionEditor(
    globalRegistry: IGlobalRegistry,
    store: IStore
): void {
    const inspectorRegistry = globalRegistry.get('inspector') as Registry | undefined;
    const viewsRegistry = inspectorRegistry?.get<Registry>('views');

    if (!viewsRegistry) {
        console.warn(
            '[Sitegeist.PaperTiger.CPX]: Could not find inspector views registry. Skipping MessageActionEditor registration.'
        );
        return;
    }

    viewsRegistry.set(
        'Sitegeist.PaperTiger.CPX/Inspector/Views/MessageActionEditor',
        {
            component: (props: any) =>
                React.createElement(
                    NeosContext.Provider,
                    {value: {globalRegistry, store}},
                    React.createElement(MessageActionEditor, props)
                )
        }
    );
}
