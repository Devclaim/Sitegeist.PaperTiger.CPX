import {escapeRegex} from './emailCompatibilityData';
import {
    CaniemailData,
    EmailClient,
    indexBySlug,
    lookupSupport
} from './emailCompatibilityRemote';

export type PreviewTheme = 'light' | 'dark';

/**
 * How a specific email client treats dark-mode rendering. Determined by
 * the client itself, not by the user. Stored in PreviewPane as a per-client
 * mapping and threaded through to the transform.
 *
 *   'aggressive' - Forces invert+hue-rotate on the whole body (mimics
 *                  Gmail Android / Outlook.com webmail forced-dark).
 *   'gentle'     - Applies color-scheme: dark plus body bg/color overrides
 *                  but otherwise honours the email's own colours (mimics
 *                  Apple Mail, Outlook macOS, Thunderbird).
 *   'none'       - Client does not adapt to dark mode at all; preview
 *                  renders the same as light (mimics Outlook Windows Word
 *                  engine, which ignores dark-mode CSS entirely).
 */
export type DarkModeBehavior = 'aggressive' | 'gentle' | 'none';

export type PreviewTransformOptions = {
    client: EmailClient | null;
    theme: PreviewTheme;
    darkModeBehavior?: DarkModeBehavior;
    compatibility: CaniemailData | null;
};

const STRIP_TAG_SLUG_TO_TAG: Record<string, string> = {
    'html-video': 'video',
    'html-audio': 'audio',
    'html-iframe': 'iframe',
    'html-form': 'form',
    'html-picture': 'picture',
    'html-svg': 'svg',
    'html-canvas': 'canvas',
    'html-script': 'script',
    'html-meter': 'meter',
    'html-progress': 'progress',
    'html-details': 'details',
    'html-dialog': 'dialog'
};

const PARTIAL_MARK_CLASS = 'cpx-compat-partial';

const NON_PROPERTY_CSS_PREFIXES: readonly string[] = [
    'css-display-',
    'css-position-',
    'css-pseudo-',
    'css-at-',
    'css-function-',
    'css-unit-'
];

const cssPropertyFromSlug = (slug: string): string | null => {
    if (!slug.startsWith('css-')) {
        return null;
    }
    for (const prefix of NON_PROPERTY_CSS_PREFIXES) {
        if (slug.startsWith(prefix)) {
            return null;
        }
    }
    if (slug.startsWith('css-properties-')) {
        return slug.slice('css-properties-'.length) || null;
    }
    return slug.slice('css-'.length) || null;
};

// ----- Strip helpers -----

const stripTags = (doc: Document, tag: string): void => {
    doc.querySelectorAll(tag).forEach((element) => {
        element.remove();
    });
};

const markPartialTags = (doc: Document, tag: string): void => {
    doc.querySelectorAll(tag).forEach((element) => {
        const existing = element.getAttribute('class') ?? '';
        if (existing.split(/\s+/).includes(PARTIAL_MARK_CLASS)) {
            return;
        }
        element.setAttribute(
            'class',
            existing.length > 0 ? `${existing} ${PARTIAL_MARK_CLASS}` : PARTIAL_MARK_CLASS
        );
    });
};

const stripInlineStyleProperty = (element: Element, prop: string): void => {
    const styleAttr = element.getAttribute('style');
    if (!styleAttr) {
        return;
    }
    const filtered = styleAttr
        .split(';')
        .map((decl) => decl.trim())
        .filter((decl) => {
            if (decl.length === 0) {
                return false;
            }
            const colonIndex = decl.indexOf(':');
            if (colonIndex < 0) {
                return true;
            }
            const declProp = decl.slice(0, colonIndex).trim().toLowerCase();
            return declProp !== prop;
        })
        .join('; ');
    if (filtered) {
        element.setAttribute('style', filtered);
    } else {
        element.removeAttribute('style');
    }
};

const stripStyleSheetProperty = (styleEl: HTMLStyleElement, prop: string): void => {
    const text = styleEl.textContent ?? '';
    if (!text) {
        return;
    }
    const re = new RegExp(
        `(^|[;{\\s])${escapeRegex(prop)}\\s*:[^;}]*(;|(?=[}]))`,
        'gi'
    );
    styleEl.textContent = text.replace(re, (_match, leading: string, trailing: string) => {
        return `${leading ?? ''}${trailing === ';' ? ';' : ''}`;
    });
};

const stripCssProperty = (doc: Document, prop: string): void => {
    doc.querySelectorAll('[style]').forEach((element) => {
        stripInlineStyleProperty(element, prop);
    });
    doc.querySelectorAll('style').forEach((element) => {
        stripStyleSheetProperty(element as HTMLStyleElement, prop);
    });
};

const stripCssAtRule = (doc: Document, name: string): void => {
    const escaped = escapeRegex(name);
    const re = new RegExp(
        `@${escaped}\\b[^{]*\\{(?:[^{}]|\\{[^}]*\\})*\\}`,
        'gi'
    );
    doc.querySelectorAll('style').forEach((styleEl) => {
        const text = styleEl.textContent ?? '';
        styleEl.textContent = text.replace(re, '');
    });
};

const ensureHead = (doc: Document): HTMLHeadElement => {
    if (doc.head) {
        return doc.head;
    }
    const head = doc.createElement('head');
    doc.documentElement.insertBefore(head, doc.documentElement.firstChild);
    return head;
};

const prependStyle = (doc: Document, css: string): void => {
    const head = ensureHead(doc);
    const styleEl = doc.createElement('style');
    styleEl.setAttribute('data-cpx-injected', 'true');
    styleEl.textContent = css;
    head.insertBefore(styleEl, head.firstChild);
};

const appendStyle = (doc: Document, css: string): void => {
    const head = ensureHead(doc);
    const styleEl = doc.createElement('style');
    styleEl.setAttribute('data-cpx-injected', 'true');
    styleEl.textContent = css;
    head.appendChild(styleEl);
};

const PARTIAL_MARK_STYLE = `
    .${PARTIAL_MARK_CLASS} {
        outline: 2px dashed #b08300 !important;
        outline-offset: 1px !important;
        position: relative !important;
    }
`;

const applyDarkTheme = (doc: Document, behavior: DarkModeBehavior): void => {
    if (behavior === 'none') {
        return;
    }
    if (behavior === 'aggressive') {
        prependStyle(doc, `
            :root { color-scheme: dark; }
            html { background: #1a1a1a; }
            html body {
                filter: invert(1) hue-rotate(180deg);
                background: #fafafa;
            }
            html body img,
            html body svg,
            html body video,
            html body picture,
            html body iframe {
                filter: invert(1) hue-rotate(180deg);
            }
        `);
        return;
    }
    prependStyle(doc, `
        :root { color-scheme: dark; }
        html, body {
            background: #1a1a1a !important;
            color: #e5e5e5 !important;
        }
    `);
};

// ----- Public API -----

export const transformPreviewMarkup = (
    markup: string,
    options: PreviewTransformOptions
): string => {
    const trimmed = markup.trim();
    if (trimmed.length === 0) {
        return markup;
    }
    const {client, theme, darkModeBehavior, compatibility} = options;
    if (!client && theme === 'light') {
        return markup;
    }

    let doc: Document;
    try {
        doc = new DOMParser().parseFromString(markup, 'text/html');
    } catch {
        return markup;
    }

    if (client && compatibility) {
        const index = indexBySlug(compatibility);

        // 1. Tag stripping + partial marking.
        for (const [slug, tag] of Object.entries(STRIP_TAG_SLUG_TO_TAG)) {
            const feature = index.get(slug);
            if (!feature) {
                continue;
            }
            const support = lookupSupport(feature, client);
            if (support === 'n') {
                stripTags(doc, tag);
            } else if (support === 'a') {
                markPartialTags(doc, tag);
            }
        }

        appendStyle(doc, PARTIAL_MARK_STYLE);

        for (const feature of compatibility.data ?? []) {
            const prop = cssPropertyFromSlug(feature.slug ?? '');
            if (!prop) {
                continue;
            }
            const support = lookupSupport(feature, client);
            if (support === 'n') {
                stripCssProperty(doc, prop);
            }
        }

        const atRuleRe = /^css-at-(?:rule-)?(.+)$/;
        for (const feature of compatibility.data ?? []) {
            const m = atRuleRe.exec(feature.slug ?? '');
            if (!m) {
                continue;
            }
            const support = lookupSupport(feature, client);
            if (support === 'n') {
                const name = m[1] === 'media-queries' ? 'media' : m[1];
                stripCssAtRule(doc, name);
            }
        }
    }

    if (theme === 'dark') {
        applyDarkTheme(doc, darkModeBehavior ?? 'gentle');
    }

    return `<!DOCTYPE html>${doc.documentElement.outerHTML}`;
};
