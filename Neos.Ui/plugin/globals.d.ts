declare module '@neos-project/react-ui-components';

declare module '@neos-project/neos-ui-redux-store' {
    type NodeChild = {
        contextPath: string;
        nodeType: string;
    };

    type Node = {
        contextPath?: string | null;
        identifier?: string | null;
        nodeType?: string | null;
        name?: string | null;
        parent?: string | null;
        children?: NodeChild[] | null;
        properties?: {
            [key: string]: any;
        };
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
