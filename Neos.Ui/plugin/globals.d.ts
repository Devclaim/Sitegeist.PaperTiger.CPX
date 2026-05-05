declare module '@neos-project/react-ui-components';

declare module '@neos-project/neos-ui-redux-store' {
    type Node = {
        contextPath?: string | null;
        identifier?: string | null;
        nodeType?: string | null;
        name?: string | null;
    };

    type Selectors = {
        CR: {
            Nodes: {
                focusedNodePathSelector: (state: any) => string | null;
                nodeByContextPath: (state: any) => (contextPath: string) => Node | null;
            };
        };
    };

    export const selectors: Selectors;
}