import React from 'react';
import {Icon} from '@neos-project/react-ui-components';
import {useI18n} from '@sitegeist/papertiger-cpx-neos-bridge';

import {
    BackToEditorButton,
    CompatDetailHeading,
    CompatEmpty,
    CompatErrorActions,
    CompatErrorBox,
    CompatFamilyCaret,
    CompatFeatureBar,
    CompatFeatureBarSegment,
    CompatFeatureDescription,
    CompatFeatureDetails,
    CompatFeatureHeader,
    CompatFeatureLabel,
    CompatFeatureList,
    CompatFeatureRow,
    CompatFeatureSlugText,
    CompatFeatureTitleText,
    CompatLegend,
    CompatLegendItem,
    CompatLoading,
    CompatNoteItem,
    CompatNoteNum,
    CompatNotesList,
    CompatOverviewItem,
    CompatOverviewRow,
    CompatOverviewValue,
    CompatPane,
    CompatPaneActions,
    CompatPaneBody,
    CompatPaneHeader,
    CompatPaneHint,
    CompatReferenceLink,
    CompatSectionHeading,
    CompatStatusDot,
    CompatVersionChip,
    CompatVersionList,
    CompatVersionNoteRef,
    CompatRefreshButton
} from './EmailActionDialog.styles';
import {
    CompatibilityAnalysis,
    DetectedFeature
} from './emailCompatibilityCompute';
import {
    EmailClient,
    UseCompatibilityDataResult
} from './emailCompatibilityRemote';

const formatPct = (value: number, totalKnown: number): number => {
    if (totalKnown === 0) {
        return 0;
    }
    return Math.round((value / totalKnown) * 100);
};

const formatFetchedAt = (timestamp: number): string => {
    const date = new Date(timestamp);
    return date.toLocaleString('de-DE', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

type EmailCompatibilityPaneProps = {
    htmlMarkup: string;
    compatibility: UseCompatibilityDataResult;
    clients: readonly EmailClient[];
    analysis: CompatibilityAnalysis;
    onBackToEditor: () => void;
};

export const EmailCompatibilityPane = React.memo((props: EmailCompatibilityPaneProps) => {
    const t = useI18n();
    const {htmlMarkup, compatibility: remote, analysis, onBackToEditor} = props;
    const trimmedHtml = htmlMarkup.trim();

    const [expandedSlugs, setExpandedSlugs] = React.useState<Set<string>>(
        () => new Set()
    );

    const toggleSlug = React.useCallback((slug: string) => {
        setExpandedSlugs((current) => {
            const next = new Set(current);
            if (next.has(slug)) {
                next.delete(slug);
            } else {
                next.add(slug);
            }
            return next;
        });
    }, []);

    const isInitialLoading = remote.status === 'loading' && !remote.data;
    const hasFatalError = remote.status === 'error' && !remote.data;

    const headerHint = React.useMemo(() => {
        if (isInitialLoading) {
            return t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.loadingData');
        }
        if (hasFatalError) {
            return t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.loadFailed');
        }
        if (remote.fetchedAt) {
            return `Datenstand: ${formatFetchedAt(remote.fetchedAt)}`;
        }
        return t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.dataSource');
    }, [isInitialLoading, hasFatalError, remote.fetchedAt]);

    const {detected, overview} = analysis;

    return (
        <CompatPane>
            <CompatPaneHeader>
                <BackToEditorButton
                    type="button"
                    onClick={onBackToEditor}
                    title={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.backToEditor')}
                    aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.backToEditor')}
                >
                    <Icon icon="arrow-left" />
                    {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.backToEditor')}
                </BackToEditorButton>
                <CompatLegend>
                    <CompatLegendItem>
                        <CompatStatusDot status="ok" />
                        {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.supported')}
                    </CompatLegendItem>
                    <CompatLegendItem>
                        <CompatStatusDot status="partial" />
                        {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.partiallySupported')}
                    </CompatLegendItem>
                    <CompatLegendItem>
                        <CompatStatusDot status="unsupported" />
                        {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.notSupported')}
                    </CompatLegendItem>
                </CompatLegend>
                <CompatPaneActions>
                    <CompatPaneHint>{headerHint}</CompatPaneHint>
                    <CompatRefreshButton
                        type="button"
                        onClick={remote.refresh}
                        disabled={remote.refreshing || isInitialLoading}
                        title={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.refreshData')}
                    >
                        {remote.refreshing ? t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.refreshing') : t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.refresh')}
                    </CompatRefreshButton>
                </CompatPaneActions>
            </CompatPaneHeader>
            <CompatPaneBody>
                {isInitialLoading ? (
                    <CompatLoading>
                        {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.loadingCompatibility')}
                    </CompatLoading>
                ) : hasFatalError ? (
                    <CompatErrorBox>
                        <div>
                            {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.loadFailed')}
                            {remote.error ? `: ${remote.error}` : ''}.
                        </div>
                        <CompatErrorActions>
                            <CompatRefreshButton
                                type="button"
                                onClick={remote.refresh}
                                disabled={remote.refreshing}
                            >
                                {remote.refreshing ? t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.retrying') : t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.retry')}
                            </CompatRefreshButton>
                        </CompatErrorActions>
                    </CompatErrorBox>
                ) : trimmedHtml.length === 0 ? (
                    <CompatEmpty>
                        {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.noHtml')}
                    </CompatEmpty>
                ) : (
                    <>
                        {remote.status === 'ready' && remote.error && (
                            <CompatErrorBox>
                                <div>
                                    {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.refreshFailed')}: {remote.error}. {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.cachedDataShown')}
                                </div>
                            </CompatErrorBox>
                        )}
                        {overview.totalCells > 0 && (
                            <CompatOverviewRow>
                                <CompatOverviewItem status="ok">
                                    <CompatOverviewValue>
                                        {overview.supportedPct.toFixed(2)}%
                                    </CompatOverviewValue>
                                </CompatOverviewItem>
                                <CompatOverviewItem status="partial">
                                    <CompatOverviewValue>
                                        {overview.partialPct.toFixed(2)}%
                                    </CompatOverviewValue>
                                </CompatOverviewItem>
                                <CompatOverviewItem status="unsupported">
                                    <CompatOverviewValue>
                                        {overview.unsupportedPct.toFixed(2)}%
                                    </CompatOverviewValue>
                                </CompatOverviewItem>
                            </CompatOverviewRow>
                        )}
                        <CompatSectionHeading>
                            {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.features')} ({detected.length})
                        </CompatSectionHeading>
                        {detected.length === 0 ? (
                            <CompatEmpty>
                                {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.noFeaturesDetected')}
                            </CompatEmpty>
                        ) : (
                            <CompatFeatureList>
                                {detected.map((feature) => (
                                    <FeatureRow
                                        key={feature.slug}
                                        feature={feature}
                                        expanded={expandedSlugs.has(feature.slug)}
                                        onToggle={() => toggleSlug(feature.slug)}
                                    />
                                ))}
                            </CompatFeatureList>
                        )}
                    </>
                )}
            </CompatPaneBody>
        </CompatPane>
    );
});

EmailCompatibilityPane.displayName = 'EmailCompatibilityPane';

type FeatureRowProps = {
    feature: DetectedFeature;
    expanded: boolean;
    onToggle: () => void;
};

const FeatureRow: React.FC<FeatureRowProps> = ({feature, expanded, onToggle}) => {
    const t = useI18n();
    const status = feature.worstStatus ?? 'ok';
    const supportedPct = formatPct(feature.supported, feature.knownClients);
    const partialPct = formatPct(feature.partial, feature.knownClients);
    const unsupportedPct = formatPct(feature.unsupported, feature.knownClients);

    const referencedNotes = React.useMemo(() => {
        const used = new Set<string>();
        for (const issue of feature.versionIssues) {
            for (const ref of issue.noteRefs) {
                used.add(ref);
            }
        }
        return Object.keys(feature.notesByNum)
            .filter((num) => used.has(num) || feature.versionIssues.length === 0)
            .sort((a, b) => parseInt(a, 10) - parseInt(b, 10));
    }, [feature.notesByNum, feature.versionIssues]);

    const showSlug = feature.title !== feature.slug;
    const barTitle = `${supportedPct}% ${t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.supported')} · ${partialPct}% ${t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.legend.partial')} · ${unsupportedPct}% ${t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.notSupported')}`;

    return (
        <CompatFeatureRow>
            <CompatFeatureHeader
                type="button"
                aria-expanded={expanded}
                onClick={onToggle}
            >
                <CompatStatusDot status={status} />
                <CompatFeatureLabel>
                    <CompatFeatureTitleText>{feature.title}</CompatFeatureTitleText>
                    {showSlug && (
                        <CompatFeatureSlugText>{feature.slug}</CompatFeatureSlugText>
                    )}
                </CompatFeatureLabel>
                <CompatFeatureBar
                    role="img"
                    aria-label={barTitle}
                    title={barTitle}
                >
                    {supportedPct > 0 && (
                        <CompatFeatureBarSegment status="ok" $pct={supportedPct}>
                            {supportedPct >= 8 ? `${supportedPct}%` : ''}
                        </CompatFeatureBarSegment>
                    )}
                    {partialPct > 0 && (
                        <CompatFeatureBarSegment status="partial" $pct={partialPct}>
                            {partialPct >= 8 ? `${partialPct}%` : ''}
                        </CompatFeatureBarSegment>
                    )}
                    {unsupportedPct > 0 && (
                        <CompatFeatureBarSegment status="unsupported" $pct={unsupportedPct}>
                            {unsupportedPct >= 8 ? `${unsupportedPct}%` : ''}
                        </CompatFeatureBarSegment>
                    )}
                </CompatFeatureBar>
                <CompatFamilyCaret $expanded={expanded} />
            </CompatFeatureHeader>
            {expanded && (
                <CompatFeatureDetails>
                    {(feature.description || feature.title !== feature.slug) && (
                        <CompatFeatureDescription>
                            {feature.description ?? feature.title}
                        </CompatFeatureDescription>
                    )}
                    {feature.versionIssues.length > 0 && (
                        <>
                            <CompatDetailHeading>
                                {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.limitedClients')}
                            </CompatDetailHeading>
                            <CompatVersionList>
                                {feature.versionIssues.map((issue, index) => (
                                    <CompatVersionChip
                                        key={`${issue.family}-${issue.platform}-${issue.version}-${index}`}
                                        status={issue.support}
                                    >
                                        {issue.familyLabel} {issue.platformLabel} ({issue.version})
                                        {issue.noteRefs.map((ref) => (
                                            <CompatVersionNoteRef key={ref}>
                                                {ref}
                                            </CompatVersionNoteRef>
                                        ))}
                                    </CompatVersionChip>
                                ))}
                            </CompatVersionList>
                        </>
                    )}
                    {referencedNotes.length > 0 && (
                        <>
                            <CompatDetailHeading>{t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.notes')}</CompatDetailHeading>
                            <CompatNotesList>
                                {referencedNotes.map((num) => (
                                    <CompatNoteItem key={num}>
                                        <CompatNoteNum>{num}</CompatNoteNum>
                                        <span>{feature.notesByNum[num]}</span>
                                    </CompatNoteItem>
                                ))}
                            </CompatNotesList>
                        </>
                    )}
                    {feature.notes && (
                        <CompatFeatureDescription>
                            {feature.notes}
                        </CompatFeatureDescription>
                    )}
                    <CompatReferenceLink
                        href={feature.referenceUrl}
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:compat.onlineReference')} ↗
                    </CompatReferenceLink>
                </CompatFeatureDetails>
            )}
        </CompatFeatureRow>
    );
};
