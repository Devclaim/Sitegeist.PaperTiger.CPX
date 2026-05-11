import React from 'react';

export const CANIEMAIL_API_URL = 'https://www.caniemail.com/api/data.json';
const CACHE_KEY = 'Sitegeist.PaperTiger.CPX.EmailCompatibility.caniemail.v1';

// ----- Types -----

export type SupportLevel = 'y' | 'a' | 'n';
export type ClientGroup = 'desktop' | 'mobile' | 'webmail';

export type EmailClient = {
    id: string;
    family: string;
    platform: string;
    group: ClientGroup;
    label: string;
};

type CaniemailVersionStats = Record<string, string>;
type CaniemailPlatformStats = Record<string, CaniemailVersionStats>;
type CaniemailFamilyStats = Record<string, CaniemailPlatformStats>;

export type CaniemailFeature = {
    slug: string;
    title?: string;
    description?: string;
    category?: string;
    notes?: string;
    notes_by_num?: Record<string, string>;
    stats?: CaniemailFamilyStats;
};

export type CaniemailNicenames = {
    family?: Record<string, string>;
    platform?: Record<string, string>;
};

export type CaniemailData = {
    data: CaniemailFeature[];
    nicenames?: CaniemailNicenames;
};

type CachedPayload = {
    data: CaniemailData;
    fetchedAt: number;
};

// ----- localStorage cache -----

const readCache = (): CachedPayload | null => {
    try {
        const raw = window.localStorage.getItem(CACHE_KEY);
        if (!raw) {
            return null;
        }
        const parsed = JSON.parse(raw) as CachedPayload;
        if (!parsed?.data || !Array.isArray(parsed.data.data) || typeof parsed.fetchedAt !== 'number') {
            return null;
        }
        return parsed;
    } catch {
        return null;
    }
};

const writeCache = (data: CaniemailData): number => {
    const fetchedAt = Date.now();
    try {
        window.localStorage.setItem(CACHE_KEY, JSON.stringify({data, fetchedAt}));
    } catch {
    }
    return fetchedAt;
};

const clearCache = (): void => {
    try {
        window.localStorage.removeItem(CACHE_KEY);
    } catch {
    }
};

// ----- Network -----

const fetchCaniemailData = async (): Promise<CaniemailData> => {
    const response = await fetch(CANIEMAIL_API_URL, {
        method: 'GET',
        credentials: 'omit',
        mode: 'cors'
    });
    if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
    }
    const payload = (await response.json()) as CaniemailData;
    if (!payload || !Array.isArray(payload.data)) {
        throw new Error('Unerwartetes Datenformat');
    }
    return payload;
};

// ----- Client derivation -----

const GROUP_ORDER: Record<ClientGroup, number> = {
    desktop: 0,
    mobile: 1,
    webmail: 2
};

const platformToGroup = (platform: string): ClientGroup => {
    if (platform === 'ios' || platform === 'android') {
        return 'mobile';
    }
    if (
        platform === 'desktop-webmail' ||
        platform === 'mobile-webmail' ||
        platform === 'webmail' ||
        platform === 'outlook-com'
    ) {
        return 'webmail';
    }
    // Includes: desktop-app, windows, macos, windows-mail, ...
    return 'desktop';
};

const formatClientLabel = (
    family: string,
    platform: string,
    nicenames: CaniemailNicenames | undefined
): string => {
    const familyLabel = nicenames?.family?.[family] ?? family;
    const platformLabel = nicenames?.platform?.[platform] ?? platform;
    return `${familyLabel} (${platformLabel})`;
};

export const deriveClients = (data: CaniemailData): EmailClient[] => {
    const seen = new Map<string, EmailClient>();

    for (const feature of data.data ?? []) {
        const stats = feature.stats;
        if (!stats || typeof stats !== 'object') {
            continue;
        }
        for (const family of Object.keys(stats)) {
            const familyStats = stats[family];
            if (!familyStats || typeof familyStats !== 'object') {
                continue;
            }
            for (const platform of Object.keys(familyStats)) {
                const id = `${family}/${platform}`;
                if (seen.has(id)) {
                    continue;
                }
                seen.set(id, {
                    id,
                    family,
                    platform,
                    group: platformToGroup(platform),
                    label: formatClientLabel(family, platform, data.nicenames)
                });
            }
        }
    }

    return Array.from(seen.values()).sort((a, b) => {
        if (a.group !== b.group) {
            return GROUP_ORDER[a.group] - GROUP_ORDER[b.group];
        }
        return a.label.localeCompare(b.label);
    });
};

// ----- Lookup helpers -----

const compareVersions = (a: string, b: string): number => {
    const partsA = a.split('.').map((part) => parseFloat(part));
    const partsB = b.split('.').map((part) => parseFloat(part));
    const length = Math.max(partsA.length, partsB.length);
    for (let i = 0; i < length; i += 1) {
        const valueA = Number.isFinite(partsA[i]) ? partsA[i] : 0;
        const valueB = Number.isFinite(partsB[i]) ? partsB[i] : 0;
        if (valueA !== valueB) {
            return valueA - valueB;
        }
    }
    return 0;
};

const parseSupportToken = (token: string | undefined): SupportLevel | null => {
    if (!token || typeof token !== 'string') {
        return null;
    }
    const first = token.trim().charAt(0).toLowerCase();
    if (first === 'y') return 'y';
    if (first === 'a') return 'a';
    if (first === 'n') return 'n';
    return null;
};

export const lookupSupport = (
    feature: CaniemailFeature,
    client: EmailClient
): SupportLevel | null => {
    const familyStats = feature.stats?.[client.family];
    if (!familyStats || typeof familyStats !== 'object') {
        return null;
    }
    const versions = familyStats[client.platform];
    if (!versions || typeof versions !== 'object') {
        return null;
    }
    const versionKeys = Object.keys(versions).sort(compareVersions);
    if (versionKeys.length === 0) {
        return null;
    }
    const latestVersion = versionKeys[versionKeys.length - 1];
    return parseSupportToken(versions[latestVersion]);
};

export const indexBySlug = (data: CaniemailData): Map<string, CaniemailFeature> => {
    const index = new Map<string, CaniemailFeature>();
    for (const feature of data.data ?? []) {
        if (feature?.slug) {
            index.set(feature.slug, feature);
        }
    }
    return index;
};

// ----- React hook -----

export type CompatibilityDataState = {
    status: 'loading' | 'ready' | 'error';
    data: CaniemailData | null;
    error: string | null;
    fetchedAt: number | null;
    refreshing: boolean;
};

export type UseCompatibilityDataResult = CompatibilityDataState & {
    refresh: () => void;
};

export const useCompatibilityData = (): UseCompatibilityDataResult => {
    const [state, setState] = React.useState<CompatibilityDataState>(() => {
        const cached = readCache();
        if (cached) {
            return {
                status: 'ready',
                data: cached.data,
                error: null,
                fetchedAt: cached.fetchedAt,
                refreshing: false
            };
        }
        return {
            status: 'loading',
            data: null,
            error: null,
            fetchedAt: null,
            refreshing: false
        };
    });

    const isMountedRef = React.useRef(true);
    React.useEffect(() => () => {
        isMountedRef.current = false;
    }, []);

    const performFetch = React.useCallback(async (isRefresh: boolean): Promise<void> => {
        setState((current) => isRefresh
            ? {...current, refreshing: true, error: null}
            : {...current, status: 'loading', error: null});

        try {
            const data = await fetchCaniemailData();
            const fetchedAt = writeCache(data);
            if (!isMountedRef.current) {
                return;
            }
            setState({
                status: 'ready',
                data,
                error: null,
                fetchedAt,
                refreshing: false
            });
        } catch (error) {
            if (!isMountedRef.current) {
                return;
            }
            const message = error instanceof Error ? error.message : 'Fetch fehlgeschlagen';
            setState((current) => ({
                status: current.data ? 'ready' : 'error',
                data: current.data,
                error: message,
                fetchedAt: current.fetchedAt,
                refreshing: false
            }));
        }
    }, []);

    // Auto-fetch only on first mount AND only when localStorage is empty.
    const didKickoffRef = React.useRef(false);
    React.useEffect(() => {
        if (didKickoffRef.current) {
            return;
        }
        didKickoffRef.current = true;
        if (state.status === 'loading' && !state.data) {
            void performFetch(false);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    const refresh = React.useCallback(() => {
        clearCache();
        void performFetch(true);
    }, [performFetch]);

    return {...state, refresh};
};
