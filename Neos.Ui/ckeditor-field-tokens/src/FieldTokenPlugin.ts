import {Plugin} from '@ckeditor/ckeditor5-core';
import {
    addListToDropdown,
    createDropdown,
    ViewModel,
    type ListDropdownItemDefinition
} from '@ckeditor/ckeditor5-ui';
import {Collection} from '@ckeditor/ckeditor5-utils';

import {InsertFieldTokenCommand} from './InsertFieldTokenCommand';
import type {FieldTokenOption} from '@sitegeist/papertiger-cpx-neos-bridge';

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

        this.editor.ui.componentFactory.add('paperTigerFieldTokenDropdown', (locale: any) => {
            const dropdownView = createDropdown(locale);
            const items = new Collection<ListDropdownItemDefinition>();

            configuredTokens.forEach(({label, token}) => {
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
                withText: true,
                tooltip: true
            });
            dropdownView.buttonView.extendTemplate({
                attributes: {
                    style: {
                        width: 'auto',
                        minWidth: '0',
                        whiteSpace: 'nowrap',
                        paddingInline: '10px'
                    }
                }
            });
            dropdownView.isEnabled = configuredTokens.length > 0;

            dropdownView.on('execute', (eventInfo: any) => {
                const token = eventInfo.source?.token;
                if (typeof token === 'string' && token !== '') {
                    this.editor.execute('insertPaperTigerFieldToken', token);
                }
            });

            return dropdownView;
        });
    }
}
