import {CaniemailData} from './emailCompatibilityRemote';

export type HtmlInfo = {
    tags: Set<string>;
    attrsByTag: Map<string, Set<string>>;
    inlineProps: Set<string>;
    inlineValuesByProp: Map<string, Set<string>>;
    cssText: string;
    hrefSchemes: Set<string>;
    imageSources: Set<string>;
};

export type DetectionRule = {
    caniemailSlug: string;
    detect: (info: HtmlInfo) => boolean;
};

// ----- Detection helpers -----

const hasTag = (tag: string) => (info: HtmlInfo) => info.tags.has(tag);

const hasAttribute = (tag: string, attr: string) => (info: HtmlInfo) =>
    Boolean(info.attrsByTag.get(tag)?.has(attr));

const hasHrefScheme = (scheme: string) => (info: HtmlInfo) =>
    info.hrefSchemes.has(scheme);

export const escapeRegex = (value: string): string =>
    value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

const cssDeclarationFor = (prop: string): RegExp =>
    new RegExp(`(?:^|[;{\\s])${escapeRegex(prop)}\\s*:`, 'i');

const hasCssProperty = (prop: string) => (info: HtmlInfo) => {
    if (info.inlineProps.has(prop)) {
        return true;
    }
    return cssDeclarationFor(prop).test(info.cssText);
};

const collectCssValuesForProp = (info: HtmlInfo, prop: string): string[] => {
    const values: string[] = [];
    const inline = info.inlineValuesByProp.get(prop);
    if (inline) {
        for (const value of inline) {
            values.push(value);
        }
    }
    const re = new RegExp(`${escapeRegex(prop)}\\s*:\\s*([^;}]+)`, 'gi');
    let match: RegExpExecArray | null;
    while ((match = re.exec(info.cssText)) !== null) {
        values.push(match[1]);
    }
    return values;
};

const hasCssPropertyValue = (prop: string, valueMatcher: RegExp) => (info: HtmlInfo) => {
    for (const value of collectCssValuesForProp(info, prop)) {
        if (valueMatcher.test(value)) {
            return true;
        }
    }
    return false;
};

const cssTextMatches = (matcher: RegExp) => (info: HtmlInfo) => {
    if (matcher.test(info.cssText)) {
        return true;
    }
    for (const values of info.inlineValuesByProp.values()) {
        for (const value of values) {
            if (matcher.test(value)) {
                return true;
            }
        }
    }
    return false;
};

const hasCustomProperty = (info: HtmlInfo): boolean => {
    for (const prop of info.inlineProps) {
        if (prop.startsWith('--')) {
            return true;
        }
    }
    return /--[a-z][\w-]*\s*:/i.test(info.cssText);
};

const hasImageExtension = (extension: string) => (info: HtmlInfo) => {
    const lower = extension.toLowerCase();
    for (const src of info.imageSources) {
        const cleaned = src.split('?')[0]?.split('#')[0]?.toLowerCase() ?? '';
        if (cleaned.endsWith(`.${lower}`)) {
            return true;
        }
    }
    return false;
};

// ----- Detection rules -----

export const DETECTION_RULES: readonly DetectionRule[] = [
    // HTML elements
    {caniemailSlug: 'html-div', detect: hasTag('div')},
    {caniemailSlug: 'html-video', detect: hasTag('video')},
    {caniemailSlug: 'html-audio', detect: hasTag('audio')},
    {caniemailSlug: 'html-iframe', detect: hasTag('iframe')},
    {caniemailSlug: 'html-form', detect: hasTag('form')},
    {caniemailSlug: 'html-picture', detect: hasTag('picture')},
    {caniemailSlug: 'html-svg', detect: hasTag('svg')},
    {caniemailSlug: 'html-canvas', detect: hasTag('canvas')},
    {caniemailSlug: 'html-script', detect: hasTag('script')},
    {caniemailSlug: 'html-meter', detect: hasTag('meter')},
    {caniemailSlug: 'html-progress', detect: hasTag('progress')},
    {caniemailSlug: 'html-details', detect: hasTag('details')},
    {caniemailSlug: 'html-dialog', detect: hasTag('dialog')},
    {caniemailSlug: 'html-style', detect: hasTag('style')},

    // HTML attributes
    {caniemailSlug: 'html-image-srcset', detect: hasAttribute('img', 'srcset')},
    {caniemailSlug: 'html-image-loading', detect: hasAttribute('img', 'loading')},

    // Anchors
    {caniemailSlug: 'html-anchor-mailto', detect: hasHrefScheme('mailto')},
    {caniemailSlug: 'html-anchor-tel', detect: hasHrefScheme('tel')},
    {caniemailSlug: 'html-anchor-sms', detect: hasHrefScheme('sms')},

    // CSS layout / display
    {caniemailSlug: 'css-display-flex', detect: hasCssPropertyValue('display', /\bflex\b/i)},
    {caniemailSlug: 'css-display-grid', detect: hasCssPropertyValue('display', /\bgrid\b/i)},
    {caniemailSlug: 'css-position-fixed', detect: hasCssPropertyValue('position', /\bfixed\b/i)},
    {caniemailSlug: 'css-position-sticky', detect: hasCssPropertyValue('position', /\bsticky\b/i)},
    {caniemailSlug: 'css-aspect-ratio', detect: hasCssProperty('aspect-ratio')},
    {
        caniemailSlug: 'css-gap',
        detect: (info) =>
            hasCssProperty('gap')(info) ||
            hasCssProperty('row-gap')(info) ||
            hasCssProperty('column-gap')(info)
    },

    // CSS visual / motion
    {
        caniemailSlug: 'css-animation',
        detect: (info) =>
            hasCssProperty('animation')(info) || cssTextMatches(/@keyframes\b/i)(info)
    },
    {caniemailSlug: 'css-transform', detect: hasCssProperty('transform')},
    {caniemailSlug: 'css-transition', detect: hasCssProperty('transition')},
    {caniemailSlug: 'css-backdrop-filter', detect: hasCssProperty('backdrop-filter')},
    {caniemailSlug: 'css-filter', detect: hasCssProperty('filter')},
    {caniemailSlug: 'css-function-linear-gradient', detect: cssTextMatches(/linear-gradient\(/i)},
    {caniemailSlug: 'css-function-radial-gradient', detect: cssTextMatches(/radial-gradient\(/i)},

    // CSS selectors
    {caniemailSlug: 'css-pseudo-class-hover', detect: cssTextMatches(/:hover\b/i)},
    {caniemailSlug: 'css-pseudo-class-checked', detect: cssTextMatches(/:checked\b/i)},
    {caniemailSlug: 'css-pseudo-element-before-after', detect: cssTextMatches(/::?(?:before|after)\b/i)},

    // CSS at-rules
    {caniemailSlug: 'css-at-supports', detect: cssTextMatches(/@supports\b/i)},
    {caniemailSlug: 'css-at-import', detect: cssTextMatches(/@import\b/i)},
    {caniemailSlug: 'css-at-font-face', detect: cssTextMatches(/@font-face\b/i)},

    // CSS misc
    {caniemailSlug: 'css-custom-properties', detect: hasCustomProperty},
    {caniemailSlug: 'css-function-calc', detect: cssTextMatches(/\bcalc\(/i)},
    {caniemailSlug: 'css-function-clamp', detect: cssTextMatches(/\bclamp\(/i)},
    {caniemailSlug: 'css-unit-rem', detect: cssTextMatches(/\d\s*rem\b/i)},
    {caniemailSlug: 'css-unit-vh-vw', detect: cssTextMatches(/\d\s*v[hw]\b/i)},

    // Image formats
    {caniemailSlug: 'image-webp', detect: hasImageExtension('webp')},
    {caniemailSlug: 'image-avif', detect: hasImageExtension('avif')}
];

const parseAutoRule = (slug: string): DetectionRule | null => {
    let m: RegExpMatchArray | null;

    // CSS display values: css-display-flex, css-display-grid, ...
    m = slug.match(/^css-display-(.+)$/);
    if (m) {
        return {
            caniemailSlug: slug,
            detect: hasCssPropertyValue('display', new RegExp(`\\b${escapeRegex(m[1])}\\b`, 'i'))
        };
    }

    // CSS position values: css-position-fixed, css-position-sticky, ...
    m = slug.match(/^css-position-(.+)$/);
    if (m) {
        return {
            caniemailSlug: slug,
            detect: hasCssPropertyValue('position', new RegExp(`\\b${escapeRegex(m[1])}\\b`, 'i'))
        };
    }

    // CSS pseudo classes: css-pseudo-class-hover, css-pseudo-class-checked, ...
    m = slug.match(/^css-pseudo-class-(.+)$/);
    if (m) {
        // Match :name preceded by start or non-colon so :: dösn't trigger.
        return {
            caniemailSlug: slug,
            detect: cssTextMatches(new RegExp(`(?:^|[^:]):${escapeRegex(m[1])}\\b`, 'i'))
        };
    }

    // CSS pseudo elements: css-pseudo-element-after, css-pseudo-element-before, ...
    m = slug.match(/^css-pseudo-element-(.+)$/);
    if (m) {
        return {
            caniemailSlug: slug,
            detect: cssTextMatches(new RegExp(`::${escapeRegex(m[1])}\\b`, 'i'))
        };
    }

    // CSS at-rules: css-at-media, css-at-keyframes, css-at-supports, ...
    // Also tolerate the older "css-at-rule-{name}" form just in case.
    m = slug.match(/^css-at-(?:rule-)?(.+)$/);
    if (m) {
        const name = m[1] === 'media-queries' ? 'media' : m[1];
        return {
            caniemailSlug: slug,
            detect: cssTextMatches(new RegExp(`@${escapeRegex(name)}\\b`, 'i'))
        };
    }

    // CSS functions: css-function-calc, css-function-linear-gradient, ...
    m = slug.match(/^css-function-(.+)$/);
    if (m) {
        return {
            caniemailSlug: slug,
            detect: cssTextMatches(new RegExp(`\\b${escapeRegex(m[1])}\\(`, 'i'))
        };
    }

    // CSS units: css-unit-rem, css-unit-vh-vw, ...
    m = slug.match(/^css-unit-(.+)$/);
    if (m) {
        return {
            caniemailSlug: slug,
            detect: cssTextMatches(new RegExp(`\\d\\s*${escapeRegex(m[1])}\\b`, 'i'))
        };
    }

    // Image formats: image-webp, image-avif, ...
    m = slug.match(/^image-(.+)$/);
    if (m) {
        return {caniemailSlug: slug, detect: hasImageExtension(m[1])};
    }

    // Legacy "css-properties-{prop}" form, kept for forward compatibility.
    m = slug.match(/^css-properties-(.+)$/);
    if (m) {
        return {caniemailSlug: slug, detect: hasCssProperty(m[1])};
    }

    // Catch-all: every other css-{name} slug is treated as a CSS property
    m = slug.match(/^css-(.+)$/);
    if (m) {
        return {caniemailSlug: slug, detect: hasCssProperty(m[1])};
    }

    return null;
};

export const deriveAutoRules = (data: CaniemailData): DetectionRule[] => {
    const manualSlugs = new Set(DETECTION_RULES.map((rule) => rule.caniemailSlug));
    const auto: DetectionRule[] = [];
    for (const feature of data.data ?? []) {
        if (!feature?.slug || manualSlugs.has(feature.slug)) {
            continue;
        }
        const rule = parseAutoRule(feature.slug);
        if (rule) {
            auto.push(rule);
        }
    }
    return auto;
};

export const getAllRules = (data: CaniemailData | null): readonly DetectionRule[] => {
    if (!data) {
        return DETECTION_RULES;
    }
    return [...DETECTION_RULES, ...deriveAutoRules(data)];
};
