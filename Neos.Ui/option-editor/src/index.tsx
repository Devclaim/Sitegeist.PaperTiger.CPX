import * as React from 'react';

type GlobalRegistry = {
    get: (key: string) => any;
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

type OptionValue = {
    label?: string;
    value?: string;
};

const LABEL_ID = 'Sitegeist.PaperTiger.CPX:Main:option.label';
const VALUE_ID = 'Sitegeist.PaperTiger.CPX:Main:option.value';
const LABEL_REQUIRED_ID = 'Sitegeist.PaperTiger.CPX:Main:option.labelRequired';
const VALUE_REQUIRED_ID = 'Sitegeist.PaperTiger.CPX:Main:option.valueRequired';

const createOptionEditor = (i18nRegistry?: I18nRegistry) => {
    const translate = (id: string, fallback: string): string =>
        i18nRegistry?.translate(id, fallback) ?? fallback;

    return {
        validator: function* (option: OptionValue) {
            if (!option.label) {
                yield {
                    field: 'label',
                    message: translate(LABEL_REQUIRED_ID, 'Label is required')
                };
            }

            if (!option.value) {
                yield {
                    field: 'value',
                    message: translate(VALUE_REQUIRED_ID, 'Value is required')
                };
            }
        },
        Preview: (props: { value: OptionValue; api: any }) => {
            const { IconCard } = props.api;

            return React.createElement(IconCard, {
                icon: 'envelope',
                title: props.value.label,
                subTitle: `${props.value.value ?? ''}`
            });
        },
        Form: (props: { api: any }) => {
            const { Field, Layout } = props.api;

            return React.createElement(
                Layout.Stack,
                null,
                React.createElement(
                    Layout.Columns,
                    { columns: 2 },
                    React.createElement(Field, {
                        name: 'label',
                        label: LABEL_ID,
                        editor: 'Neos.Neos/Inspector/Editors/TextFieldEditor'
                    }),
                    React.createElement(Field, {
                        name: 'value',
                        label: VALUE_ID,
                        editor: 'Neos.Neos/Inspector/Editors/TextFieldEditor'
                    })
                )
            );
        }
    };
};

export function registerOptionEditor(globalRegistry: GlobalRegistry): void {
    const editorsRegistry = globalRegistry.get('@sitegeist/inspectorgadget/editors');

    if (!editorsRegistry) {
        console.warn('[Sitegeist.PaperTiger.CPX]: Could not find InspectorGadget editors registry.');
        return;
    }

    const i18nRegistry = globalRegistry.get('i18n') as I18nRegistry | undefined;

    editorsRegistry.set(
        'Sitegeist\\PaperTiger\\Domain\\OptionSpecification',
        createOptionEditor(i18nRegistry)
    );
}
