import { Command } from '@ckeditor/ckeditor5-core';

export class InsertFieldTokenCommand extends Command {
    refresh(): void {
        this.isEnabled = true;
    }

    execute(token?: string): void {
        if (typeof token !== 'string' || token === '') {
            return;
        }

        const { editor } = this;

        editor.model.change(writer => {
            const text = writer.createText(`${token} `);
            const selection = editor.model.document.selection;

            const insertedRange = editor.model.insertContent(text, selection);

            writer.setSelection(insertedRange.end);
        });

        editor.editing.view.focus();
    }
}