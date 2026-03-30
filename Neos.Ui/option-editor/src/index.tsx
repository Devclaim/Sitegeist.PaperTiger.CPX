import * as React from 'react';

type GlobalRegistry = {
    get: (key: string) => any;
};

type OptionValue = {
    label?: string;
    value?: string;
};

const OptionEditor = {
    validator: function* (option: OptionValue) {
        if (!option.label) {
            yield {
                field: 'label',
                message: 'Label is required'
            };
        }

        if (!option.value) {
            yield {
                field: 'value',
                message: 'Value is required'
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
                    label: 'Label',
                    editor: 'Neos.Neos/Inspector/Editors/TextFieldEditor'
                }),
                React.createElement(Field, {
                    name: 'value',
                    label: 'Value',
                    editor: 'Neos.Neos/Inspector/Editors/TextFieldEditor'
                })
            )
        );
    }
};

export function registerOptionEditor(globalRegistry: GlobalRegistry): void {
    const editorsRegistry = globalRegistry.get('@sitegeist/inspectorgadget/editors');

    if (!editorsRegistry) {
        console.warn('[Sitegeist.PaperTiger.CPX]: Could not find InspectorGadget editors registry.');
        return;
    }

    editorsRegistry.set('Sitegeist\\PaperTiger\\Domain\\OptionSpecification', OptionEditor);
}
