import {selectors} from '@neos-project/neos-ui-redux-store';

export type FocusedNodeState = {
    nodeIdentifier: string | null;
    contextPath: string | null;
};

export const resolveFocusedNodeState = (state: any): FocusedNodeState => {
    const focusedNodeContextPath =
        selectors.CR.Nodes.focusedNodePathSelector(state);

    if (!focusedNodeContextPath) {
        return {
            nodeIdentifier: null,
            contextPath: null
        };
    }

    const getNodeByContextPath =
        selectors.CR.Nodes.nodeByContextPath(state);

    const focusedNode = getNodeByContextPath(focusedNodeContextPath);

    return {
        nodeIdentifier: focusedNode?.identifier ?? null,
        contextPath: focusedNode?.contextPath ?? focusedNodeContextPath
    };
};