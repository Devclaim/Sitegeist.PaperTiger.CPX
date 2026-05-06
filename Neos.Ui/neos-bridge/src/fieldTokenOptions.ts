import {selectors} from '@neos-project/neos-ui-redux-store';

type NodeChild = {
    contextPath: string;
    nodeType?: string | null;
};

type Node = {
    contextPath?: string | null;
    parent?: string | null;
    children?: NodeChild[] | null;
    label?: string | null;
    properties?: {
        [key: string]: unknown;
    };
};

export type FieldTokenOption = {
    label: string;
    token: string;
    name: string;
    contextPath: string;
};

export const resolveFocusedNodeContextPath = (state: any): string | null =>
    selectors.CR.Nodes.focusedNodePathSelector(state) ?? null;

const getNodeByContextPath = (state: any, contextPath: string): Node | null =>
    state?.cr?.nodes?.byContextPath?.[contextPath] ?? null;

const readFieldName = (node: Node): string | null => {
    const candidate = node.properties?.name;
    return typeof candidate === 'string' && candidate.trim() !== '' ?
        candidate.trim() :
        null;
};

const readFieldLabel = (node: Node, fallbackName: string): string => {
    const propertyLabel = node.properties?.label;

    if (typeof propertyLabel === 'string' && propertyLabel.trim() !== '') {
        return propertyLabel.trim();
    }

    if (typeof node.label === 'string' && node.label.trim() !== '') {
        return node.label.trim();
    }

    return fallbackName;
};

export const resolveFieldTokenOptions = (
    state: any,
    contextPath: string | null
): FieldTokenOption[] => {
    if (!contextPath) {
        console.warn(
            '[Sitegeist.PaperTiger.CPX] FieldTokenOptions: Missing contextPath.'
        );
        return [];
    }

    const focusedNode = getNodeByContextPath(state, contextPath);

    const childReferences = focusedNode?.children ?? [];
    const seenNames = new Set<string>();
    const tokens: FieldTokenOption[] = [];

    childReferences.forEach((childReference) => {
        if (!childReference?.contextPath) {
            return;
        }

        const siblingNode = getNodeByContextPath(state, childReference.contextPath);
        if (!siblingNode) {
            return;
        }

        const fieldName = readFieldName(siblingNode);
        const fieldLabel = fieldName ?
            readFieldLabel(siblingNode, fieldName) :
            null;

        if (!fieldName || seenNames.has(fieldName)) {
            return;
        }

        seenNames.add(fieldName);
        tokens.push({
            label: fieldLabel ?? fieldName,
            token: `{${fieldName}}`,
            name: fieldName,
            contextPath: childReference.contextPath
        });
    });

    return tokens;
};
