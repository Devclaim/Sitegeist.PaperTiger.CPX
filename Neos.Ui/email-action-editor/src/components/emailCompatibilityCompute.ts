import React from 'react';

import {
    DetectionRule,
    HtmlInfo,
    getAllRules
} from './emailCompatibilityData';
import {
    CaniemailFeature,
    CaniemailNicenames,
    EmailClient,
    SupportLevel,
    UseCompatibilityDataResult,
    indexBySlug,
    lookupSupport
} from './emailCompatibilityRemote';

// ----- Types -----

export type IssueStatus = 'partial' | 'unsupported';
export type ClientStatus = 'ok' | 'partial' | 'unsupported';

export type ClientIssue = {
    client: EmailClient;
    status: IssueStatus;
};

export type VersionIssue = {
    family: string;
    platform: string;
    version: string;
    familyLabel: string;
    platformLabel: string;
    support: IssueStatus;
    noteRefs: string[];
};

export type DetectedFeature = {
    slug: string;
    title: string;
    description?: string;
    notes?: string;
    notesByNum: Record<string, string>;
    perClientSupport: Map<string, SupportLevel>;
    issues: ClientIssue[];
    supported: number;
    partial: number;
    unsupported: number;
    knownClients: number;
    versionIssues: VersionIssue[];
    referenceUrl: string;
    worstStatus: IssueStatus | null;
};

export type ClientSummary = {
    client: EmailClient;
    supported: number;
    partial: number;
    unsupported: number;
    known: number;
    score: number; // 0-100
    status: ClientStatus;
};

export type FeatureOverview = {
    supportedCount: number;
    partialCount: number;
    unsupportedCount: number;
    totalCells: number;
    supportedPct: number;
    partialPct: number;
    unsupportedPct: number;
};

export type CompatibilityAnalysis = {
    detected: DetectedFeature[];
    issueFeatures: DetectedFeature[];
    clientSummaries: ClientSummary[];
    averageScore: number | null;
    overview: FeatureOverview;
    okClientCount: number;
    totalClientCount: number;
};

// ----- HTML parsing -----

const collectInlineStyle = (
    styleValue: string,
    props: Set<string>,
    valuesByProp: Map<string, Set<string>>
): void => {
    const declarations = styleValue.split(';');
    for (const declaration of declarations) {
        const colonIndex = declaration.indexOf(':');
        if (colonIndex < 0) {
            continue;
        }
        const prop = declaration.slice(0, colonIndex).trim().toLowerCase();
        const value = declaration.slice(colonIndex + 1).trim();
        if (!prop) {
            continue;
        }
        props.add(prop);
        let bucket = valuesByProp.get(prop);
        if (!bucket) {
            bucket = new Set();
            valuesByProp.set(prop, bucket);
        }
        bucket.add(value);
    }
};

const extractHrefScheme = (href: string): string | null => {
    const trimmed = href.trim();
    const colonIndex = trimmed.indexOf(':');
    if (colonIndex <= 0) {
        return null;
    }
    return trimmed.slice(0, colonIndex).toLowerCase();
};

export const parseHtmlInfo = (markup: string): HtmlInfo => {
    const info: HtmlInfo = {
        tags: new Set<string>(),
        attrsByTag: new Map<string, Set<string>>(),
        inlineProps: new Set<string>(),
        inlineValuesByProp: new Map<string, Set<string>>(),
        cssText: '',
        hrefSchemes: new Set<string>(),
        imageSources: new Set<string>()
    };

    if (!markup.trim()) {
        return info;
    }

    let doc: Document;
    try {
        doc = new DOMParser().parseFromString(markup, 'text/html');
    } catch {
        return info;
    }

    const styleChunks: string[] = [];
    const allElements = doc.querySelectorAll('*');

    allElements.forEach((element) => {
        const tagName = element.tagName.toLowerCase();
        info.tags.add(tagName);

        let attrBucket = info.attrsByTag.get(tagName);
        if (!attrBucket) {
            attrBucket = new Set<string>();
            info.attrsByTag.set(tagName, attrBucket);
        }

        for (const attr of Array.from(element.attributes)) {
            const attrName = attr.name.toLowerCase();
            attrBucket.add(attrName);

            if (attrName === 'style') {
                collectInlineStyle(attr.value, info.inlineProps, info.inlineValuesByProp);
            }

            if (
                attrName === 'href' &&
                (tagName === 'a' || tagName === 'area' || tagName === 'link')
            ) {
                const scheme = extractHrefScheme(attr.value);
                if (scheme) {
                    info.hrefSchemes.add(scheme);
                }
            }

            if (attrName === 'src' && (tagName === 'img' || tagName === 'source')) {
                info.imageSources.add(attr.value);
            }
            if (attrName === 'srcset' && (tagName === 'img' || tagName === 'source')) {
                const candidates = attr.value
                    .split(',')
                    .map((entry) => entry.trim().split(/\s+/)[0])
                    .filter((entry) => Boolean(entry));
                for (const candidate of candidates) {
                    info.imageSources.add(candidate);
                }
            }
        }

        if (tagName === 'style') {
            styleChunks.push(element.textContent ?? '');
        }
    });

    info.cssText = styleChunks.join('\n');
    return info;
};

const parseSupportEntry = (
    token: string | undefined
): {level: SupportLevel | null; noteRefs: string[]} => {
    if (!token || typeof token !== 'string') {
        return {level: null, noteRefs: []};
    }
    const trimmed = token.trim().toLowerCase();
    let level: SupportLevel | null = null;
    const first = trimmed.charAt(0);
    if (first === 'y') level = 'y';
    else if (first === 'a') level = 'a';
    else if (first === 'n') level = 'n';

    const noteRefs: string[] = [];
    const matches = token.matchAll(/#(\d+)/g);
    for (const match of matches) {
        noteRefs.push(match[1]);
    }
    return {level, noteRefs};
};

const titleForFeature = (feature: CaniemailFeature, slug: string): string => {
    if (feature.title && feature.title.trim().length > 0) {
        return feature.title;
    }
    return slug;
};

const referenceUrlForSlug = (slug: string): string =>
    `https://www.caniemail.com/features/${slug}/`;

const collectVersionIssues = (
    feature: CaniemailFeature,
    nicenames: CaniemailNicenames | undefined
): VersionIssue[] => {
    const familyNames = nicenames?.family ?? {};
    const platformNames = nicenames?.platform ?? {};
    const issues: VersionIssue[] = [];
    const stats = feature.stats;
    if (!stats) {
        return issues;
    }
    for (const family of Object.keys(stats)) {
        const familyStats = stats[family];
        if (!familyStats || typeof familyStats !== 'object') {
            continue;
        }
        const familyLabel = familyNames[family] ?? family;
        for (const platform of Object.keys(familyStats)) {
            const platformStats = familyStats[platform];
            if (!platformStats || typeof platformStats !== 'object') {
                continue;
            }
            const platformLabel = platformNames[platform] ?? platform;
            for (const version of Object.keys(platformStats)) {
                const parsed = parseSupportEntry(platformStats[version]);
                if (parsed.level === 'a' || parsed.level === 'n') {
                    issues.push({
                        family,
                        platform,
                        version,
                        familyLabel,
                        platformLabel,
                        support: parsed.level === 'n' ? 'unsupported' : 'partial',
                        noteRefs: parsed.noteRefs
                    });
                }
            }
        }
    }
    return issues;
};

export const computeDetected = (
    info: HtmlInfo,
    caniemailIndex: Map<string, CaniemailFeature>,
    nicenames: CaniemailNicenames | undefined,
    clients: readonly EmailClient[],
    rules: readonly DetectionRule[]
): DetectedFeature[] => {
    const detected: DetectedFeature[] = [];

    for (const rule of rules) {
        let used = false;
        try {
            used = rule.detect(info);
        } catch {
            used = false;
        }
        if (!used) {
            continue;
        }

        const remoteFeature = caniemailIndex.get(rule.caniemailSlug);
        if (!remoteFeature) {
            continue;
        }

        const perClientSupport = new Map<string, SupportLevel>();
        const issues: ClientIssue[] = [];
        let supported = 0;
        let partial = 0;
        let unsupported = 0;
        let worst: IssueStatus | null = null;

        for (const client of clients) {
            const support = lookupSupport(remoteFeature, client);
            if (!support) {
                continue;
            }
            perClientSupport.set(client.id, support);
            if (support === 'y') {
                supported += 1;
            } else if (support === 'a') {
                partial += 1;
                issues.push({client, status: 'partial'});
                if (worst !== 'unsupported') {
                    worst = 'partial';
                }
            } else if (support === 'n') {
                unsupported += 1;
                issues.push({client, status: 'unsupported'});
                worst = 'unsupported';
            }
        }

        detected.push({
            slug: rule.caniemailSlug,
            title: titleForFeature(remoteFeature, rule.caniemailSlug),
            description: remoteFeature.description,
            notes: remoteFeature.notes,
            notesByNum: remoteFeature.notes_by_num ?? {},
            perClientSupport,
            issues,
            supported,
            partial,
            unsupported,
            knownClients: supported + partial + unsupported,
            versionIssues: collectVersionIssues(remoteFeature, nicenames),
            referenceUrl: referenceUrlForSlug(rule.caniemailSlug),
            worstStatus: worst
        });
    }

    const rank = (status: IssueStatus | null): number =>
        status === 'unsupported' ? 0 : status === 'partial' ? 1 : 2;

    return detected.sort((a, b) => {
        const statusDelta = rank(a.worstStatus) - rank(b.worstStatus);
        if (statusDelta !== 0) {
            return statusDelta;
        }
        if (a.unsupported !== b.unsupported) {
            return b.unsupported - a.unsupported;
        }
        if (a.partial !== b.partial) {
            return b.partial - a.partial;
        }
        return a.title.localeCompare(b.title);
    });
};

export const summarizeClients = (
    detected: readonly DetectedFeature[],
    clients: readonly EmailClient[]
): ClientSummary[] => {
    const byClient = new Map<string, ClientSummary>();
    for (const client of clients) {
        byClient.set(client.id, {
            client,
            supported: 0,
            partial: 0,
            unsupported: 0,
            known: 0,
            score: 100,
            status: 'ok'
        });
    }
    for (const entry of detected) {
        for (const [clientId, support] of entry.perClientSupport) {
            const summary = byClient.get(clientId);
            if (!summary) {
                continue;
            }
            summary.known += 1;
            if (support === 'y') summary.supported += 1;
            else if (support === 'a') summary.partial += 1;
            else if (support === 'n') summary.unsupported += 1;
        }
    }
    for (const summary of byClient.values()) {
        if (summary.known === 0) {
            summary.score = 100;
            summary.status = 'ok';
            continue;
        }
        const numerator = summary.supported + 0.5 * summary.partial;
        summary.score = Math.round((numerator / summary.known) * 100);
        summary.status =
            summary.unsupported > 0
                ? 'unsupported'
                : summary.partial > 0
                    ? 'partial'
                    : 'ok';
    }
    return Array.from(byClient.values()).sort((a, b) => {
        if (a.score !== b.score) return a.score - b.score;
        if (a.unsupported !== b.unsupported) return b.unsupported - a.unsupported;
        return a.client.label.localeCompare(b.client.label);
    });
};

export const computeAverageScore = (
    summaries: readonly ClientSummary[]
): number | null => {
    if (summaries.length === 0) return null;
    const scored = summaries.filter((entry) => entry.known > 0);
    if (scored.length === 0) return null;
    const total = scored.reduce((sum, entry) => sum + entry.score, 0);
    return Math.round(total / scored.length);
};

// ----- Slug-based overview -----

export const computeOverview = (
    detected: readonly DetectedFeature[]
): FeatureOverview => {
    let supportedCount = 0;
    let partialCount = 0;
    let unsupportedCount = 0;
    for (const feature of detected) {
        supportedCount += feature.supported;
        partialCount += feature.partial;
        unsupportedCount += feature.unsupported;
    }
    const totalCells = supportedCount + partialCount + unsupportedCount;
    return {
        supportedCount,
        partialCount,
        unsupportedCount,
        totalCells,
        supportedPct: totalCells > 0 ? (supportedCount / totalCells) * 100 : 0,
        partialPct: totalCells > 0 ? (partialCount / totalCells) * 100 : 0,
        unsupportedPct: totalCells > 0 ? (unsupportedCount / totalCells) * 100 : 0
    };
};

const useDebouncedValue = <T,>(value: T, delayMs: number): T => {
    const [debounced, setDebounced] = React.useState(value);
    React.useEffect(() => {
        const handle = window.setTimeout(() => setDebounced(value), delayMs);
        return () => window.clearTimeout(handle);
    }, [value, delayMs]);
    return debounced;
};

const EMPTY_ANALYSIS: CompatibilityAnalysis = {
    detected: [],
    issueFeatures: [],
    clientSummaries: [],
    averageScore: null,
    overview: {
        supportedCount: 0,
        partialCount: 0,
        unsupportedCount: 0,
        totalCells: 0,
        supportedPct: 0,
        partialPct: 0,
        unsupportedPct: 0
    },
    okClientCount: 0,
    totalClientCount: 0
};

export const useCompatibilityAnalysis = (
    html: string,
    compatibility: UseCompatibilityDataResult,
    clients: readonly EmailClient[]
): CompatibilityAnalysis => {
    const debouncedHtml = useDebouncedValue(html, 350);
    const trimmed = debouncedHtml.trim();

    const caniemailIndex = React.useMemo<Map<string, CaniemailFeature> | null>(() => {
        if (!compatibility.data) {
            return null;
        }
        return indexBySlug(compatibility.data);
    }, [compatibility.data]);

    const allRules = React.useMemo<readonly DetectionRule[]>(
        () => getAllRules(compatibility.data),
        [compatibility.data]
    );

    const detected = React.useMemo<DetectedFeature[]>(() => {
        if (!caniemailIndex || clients.length === 0 || trimmed.length === 0) {
            return [];
        }
        const info = parseHtmlInfo(trimmed);
        return computeDetected(
            info,
            caniemailIndex,
            compatibility.data?.nicenames,
            clients,
            allRules
        );
    }, [caniemailIndex, clients, trimmed, allRules, compatibility.data?.nicenames]);

    const issueFeatures = React.useMemo<DetectedFeature[]>(
        () => detected.filter((entry) => entry.worstStatus !== null),
        [detected]
    );

    const clientSummaries = React.useMemo<ClientSummary[]>(
        () => summarizeClients(detected, clients),
        [detected, clients]
    );

    const averageScore = React.useMemo<number | null>(
        () => computeAverageScore(clientSummaries),
        [clientSummaries]
    );

    const overview = React.useMemo<FeatureOverview>(
        () => computeOverview(detected),
        [detected]
    );

    if (detected.length === 0) {
        return {
            ...EMPTY_ANALYSIS,
            clientSummaries,
            averageScore,
            totalClientCount: clientSummaries.length,
            okClientCount: clientSummaries.filter((entry) => entry.status === 'ok').length
        };
    }

    const okClientCount = clientSummaries.filter((entry) => entry.status === 'ok').length;

    return {
        detected,
        issueFeatures,
        clientSummaries,
        averageScore,
        overview,
        okClientCount,
        totalClientCount: clientSummaries.length
    };
};
