import manifest from '@neos-project/neos-ui-extensibility';

import {
    registerMessageActionEditorStore
} from '@sitegeist/papertiger-cpx-neos-bridge';

import {
    registerEmailActionDialogContainer,
    registerEmailActionEditor
} from '@sitegeist/papertiger-cpx-email-action-editor';

import {registerOptionEditor} from '@sitegeist/papertiger-cpx-option-editor';
import {registerRedirectActionEditor} from '@sitegeist/papertiger-cpx-redirect-action-editor';
import {registerMessageActionEditor} from '@sitegeist/papertiger-cpx-message-action-editor';

manifest('@sitegeist/papertiger-cpx', {}, globalRegistry => {
    registerMessageActionEditorStore(globalRegistry);

    registerOptionEditor(globalRegistry);
    registerEmailActionDialogContainer(globalRegistry);
    registerEmailActionEditor(globalRegistry);
    registerRedirectActionEditor(globalRegistry);
    registerMessageActionEditor(globalRegistry);
});
