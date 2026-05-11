import manifest from '@neos-project/neos-ui-extensibility';

import {
    registerMessageActionEditorStore
} from '@sitegeist/papertiger-cpx-neos-bridge';

import {
    registerEmailActionDialogContainer,
    registerEmailActionEditor
} from '@sitegeist/papertiger-cpx-email-action-editor';

import {registerFieldTokenCkEditorIntegration} from '@sitegeist/papertiger-cpx-ckeditor-field-tokens';
import {registerOptionEditor} from '@sitegeist/papertiger-cpx-option-editor';
import {registerRedirectActionEditor} from '@sitegeist/papertiger-cpx-redirect-action-editor';
import {registerMessageActionEditor} from '@sitegeist/papertiger-cpx-message-action-editor';

manifest('@sitegeist/papertiger-cpx', {}, (globalRegistry, {store}) => {
    registerMessageActionEditorStore(globalRegistry, store);

    registerOptionEditor(globalRegistry);
    registerEmailActionDialogContainer(globalRegistry, store);
    registerEmailActionEditor(globalRegistry, store);
    registerRedirectActionEditor(globalRegistry);
    registerMessageActionEditor(globalRegistry, store);
    registerFieldTokenCkEditorIntegration(globalRegistry, store);
});
