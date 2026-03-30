import manifest from '@neos-project/neos-ui-extensibility';
import { registerOptionEditor } from '@sitegeist/papertiger-cpx-option-editor';

manifest('@sitegeist/papertiger-cpx', {}, (globalRegistry) => {
    registerOptionEditor(globalRegistry);
});
