import React from 'react';

import {
    PaneTitle,
    TokenBar as TokenBarContainer,
    TokenButton
} from './EmailActionDialog.styles';
import { useI18n } from '@sitegeist/papertiger-cpx-neos-bridge';

type TokenBarProps = {
    fieldTokens: Array<{ name: string; label: string; token: string }>;
    onInsertToken: (token: string) => void;
};

export const TokenBar: React.FC<TokenBarProps> = ({
    fieldTokens,
    onInsertToken
}) => {
    const t = useI18n()
    
    return (
        <TokenBarContainer>
            <PaneTitle>{t('fields','Fields',{},'Sitegeist.PaperTiger.CPX','Main')}</PaneTitle>
            {fieldTokens.map((fieldToken) => (
                <TokenButton
                    key={fieldToken.name}
                    type="button"
                    title={fieldToken.token}
                    onClick={() => onInsertToken(fieldToken.token)}
                >
                    {fieldToken.label}
                </TokenButton>
            ))}
        </TokenBarContainer>
    )
};
