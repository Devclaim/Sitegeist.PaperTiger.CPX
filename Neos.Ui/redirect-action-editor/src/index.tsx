import React from 'react';
import styled from 'styled-components';

type GlobalRegistry = {
    get: (key: string) => any;
};

const Container = styled.div<{ highlight?: boolean }>`
    padding: 12px;
    border: 1px solid #d7d7d7;
    border-radius: 6px;
    background-color: #fafafa;

    ${({highlight}) => highlight && `
        box-shadow: 0 0 0 2px #ff8700;
        border-radius: 2px;
    `}
`;

const RedirectActionEditor = {
    component: (props: any) => {
        const value = props.value && typeof props.value === 'object' ? props.value : null;

        return (
            <Container highlight={props.highlight}>
                <div
                    style={{
                        fontWeight: 600,
                        marginBottom: '6px'
                    }}
                >
                    Redirect Action Editor
                </div>
                <div
                    style={{
                        fontSize: '12px',
                        color: '#666',
                        marginBottom: value ? '8px' : '0'
                    }}
                >
                    Placeholder registration for the future redirect editor.
                </div>
                {value ? (
                    <pre
                        style={{
                            margin: 0,
                            padding: '8px',
                            overflowX: 'auto',
                            fontSize: '11px',
                            backgroundColor: '#fff',
                            border: '1px solid #ececec',
                            borderRadius: '4px'
                        }}
                    >
                        {JSON.stringify(value, null, 2)}
                    </pre>
                ) : null}
            </Container>
        );
    }
};

export function registerRedirectActionEditor(globalRegistry: GlobalRegistry): void {
    const inspectorRegistry = globalRegistry.get('inspector');
    if (!inspectorRegistry) {
        console.warn('[Sitegeist.PaperTiger.CPX]: Could not find inspector registry.');
        console.warn('[Sitegeist.PaperTiger.CPX]: Skipping registration of RedirectActionEditor...');
        return;
    }

    const editorsRegistry = inspectorRegistry.get('editors');
    if (!editorsRegistry) {
        console.warn('[Sitegeist.PaperTiger.CPX]: Could not find inspector editors registry.');
        console.warn('[Sitegeist.PaperTiger.CPX]: Skipping registration of RedirectActionEditor...');
        return;
    }

    editorsRegistry.set('Sitegeist.PaperTiger.CPX/Inspector/Editors/RedirectActionEditor', RedirectActionEditor);
}
