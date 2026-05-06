import { Plugin } from '@ckeditor/ckeditor5-core';
import {
    addListToDropdown,
    createDropdown,
    ViewModel,
    type ListDropdownItemDefinition
} from '@ckeditor/ckeditor5-ui';
import { Collection } from '@ckeditor/ckeditor5-utils';

import { InsertFieldTokenCommand } from './InsertFieldTokenCommand';
import type { FieldTokenOption } from '@sitegeist/papertiger-cpx-neos-bridge';

const fieldIcon = `
<svg
    class="paper-tiger-field-token-icon"
    viewBox="0 0 20 20"
    xmlns="http://www.w3.org/2000/svg"
>
    <path
        d="M5.5 4
           C3.8 4 3 4.8 3 6.5v1
           c0 .7-.3 1-1 1v3
           c.7 0 1 .3 1 1v1
           C3 15.2 3.8 16 5.5 16H7v-2H5.8
           c-.6 0-.8-.2-.8-.8v-1
           c0-1.1-.5-1.9-1.4-2.2
           .9-.3 1.4-1.1 1.4-2.2v-1
           c0-.6.2-.8.8-.8H7V4H5.5z"
        fill="currentColor"
    />

    <text
        x="10"
        y="12.2"
        text-anchor="middle"
        font-size="5"
        font-family="Arial, sans-serif"
        font-weight="700"
        fill="currentColor"
    >
        xyz
    </text>

    <path
        d="M14.5 4H13v2h1.2
           c.6 0 .8.2.8.8v1
           c0 1.1.5 1.9 1.4 2.2
           -.9.3-1.4 1.1-1.4 2.2v1
           c0 .6-.2.8-.8.8H13v2h1.5
           c1.7 0 2.5-.8 2.5-2.5v-1
           c0-.7.3-1 1-1v-3
           c-.7 0-1-.3-1-1v-1
           C17 4.8 16.2 4 14.5 4z"
        fill="currentColor"
    />
</svg>
`;

export class FieldTokenPlugin extends Plugin {
    init(): void {
        const configuredTokens =
            (this.editor.config.get('paperTigerFieldTokens.tokens') as FieldTokenOption[] | undefined) ?? [];

        const dropdownLabel =
            (this.editor.config.get('paperTigerFieldTokens.label') as string | undefined) ?? 'Fields';

        this.editor.commands.add(
            'insertPaperTigerFieldToken',
            new InsertFieldTokenCommand(this.editor)
        );

        this.editor.ui.componentFactory.add('paperTigerFieldTokenDropdown', locale => {
            const dropdownView = createDropdown(locale);
            const items = new Collection<ListDropdownItemDefinition>();

            dropdownView.extendTemplate({
                attributes: {
                    class: ['paper-tiger-field-token-dropdown']
                }
            })

            configuredTokens.forEach(({ label, token }) => {
                items.add({
                    type: 'button',
                    model: new ViewModel({
                        label,
                        withText: true,
                        token
                    })
                });
            });

            addListToDropdown(dropdownView, items);

            dropdownView.buttonView.set({
                label: dropdownLabel,
                icon: fieldIcon,
                withText: true,
                tooltip: true
            });

            dropdownView.buttonView.extendTemplate({
                attributes: {
                    class: [ 'paper-tiger-field-token-dropdown-button' ]
                }
            });

            dropdownView.isEnabled = configuredTokens.length > 0;

            dropdownView.on('execute', (eventInfo: any) => {
                const token = eventInfo.source?.token;

                if (typeof token === 'string' && token !== '') {
                    this.editor.execute('insertPaperTigerFieldToken', token);
                    this.editor.editing.view.focus();
                }
            });

            return dropdownView;
        });
    }
}