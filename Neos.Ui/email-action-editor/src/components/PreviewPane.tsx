import React from 'react';
import {Icon} from '@neos-project/react-ui-components';
import {useI18n} from '@sitegeist/papertiger-cpx-neos-bridge';

import {
    CompatBadge,
    DeviceToggle,
    DeviceToggleButton,
    Pane,
    PaneHeader,
    PaneHeaderGroup,
    PaneTitle,
    PlainPreview,
    PreviewControls,
    PreviewControlGroup,
    PreviewSelect,
    PreviewFrame,
    PreviewFrameStage,
} from './EmailActionDialog.styles';
import {EmailClient} from './emailCompatibilityRemote';
import {transformPreviewMarkup} from './emailPreviewTransform';
import {UseCompatibilityDataResult} from './emailCompatibilityRemote';

type PreviewPaneProps = {
    format: 'html' | 'plaintext';
    previewMarkup: string;
    previewPlaintext: string;
    subject: string;
    compatibility: UseCompatibilityDataResult | null;
    clients: readonly EmailClient[];
    compatibilityScore: number | null;
    onShowCompatibility: () => void;
};

type CompatStatus = 'ok' | 'partial' | 'unsupported';

const statusFromScore = (score: number): CompatStatus => {
    if (score >= 90) return 'ok';
    if (score >= 70) return 'partial';
    return 'unsupported';
};

const PREVIEW_TARGET_IDS: readonly string[] = [
    'gmail/desktop-webmail',
    'gmail/android',
    'outlook/windows',
    'outlook/outlook-com',
    'apple-mail/macos',
    'apple-mail/ios'
];

const DEFAULT_CLIENT_ID = 'gmail/desktop-webmail';

type DeviceMode = 'phone' | 'tablet' | 'desktop';

const DEVICE_MAX_WIDTH: Record<DeviceMode, string | undefined> = {
    phone: '375px',
    tablet: '768px',
    desktop: undefined
};

const DEVICE_ICON: Record<DeviceMode, string> = {
    phone: 'mobile-alt',
    tablet: 'tablet-alt',
    desktop: 'desktop'
};

const DEVICE_ORDER: readonly DeviceMode[] = ['phone', 'tablet', 'desktop'];

export const PreviewPane = React.memo((props: PreviewPaneProps) => {
    const t = useI18n();
    const {
        format,
        previewMarkup,
        previewPlaintext,
        compatibility,
        clients,
        compatibilityScore,
        onShowCompatibility
    } = props;

    const [selectedClientId, setSelectedClientId] = React.useState<string>(DEFAULT_CLIENT_ID);
    const [device, setDevice] = React.useState<DeviceMode>('desktop');
    const previewClients = React.useMemo<readonly EmailClient[]>(() => {
        const byId = new Map(clients.map((client) => [client.id, client]));
        return PREVIEW_TARGET_IDS
            .map((id) => byId.get(id))
            .filter((client): client is EmailClient => client !== undefined);
    }, [clients]);

    const selectedClient = React.useMemo<EmailClient | null>(
        () => previewClients.find((client) => client.id === selectedClientId) ?? null,
        [previewClients, selectedClientId]
    );

    React.useEffect(() => {
        if (previewClients.length === 0) {
            return;
        }
        if (previewClients.some((client) => client.id === selectedClientId)) {
            return;
        }
        const fallback = previewClients.some((client) => client.id === DEFAULT_CLIENT_ID)
            ? DEFAULT_CLIENT_ID
            : previewClients[0].id;
        setSelectedClientId(fallback);
    }, [previewClients, selectedClientId]);

    const transformedMarkup = React.useMemo(() => {
        if (format !== 'html') {
            return '';
        }
        return transformPreviewMarkup(previewMarkup, {
            client: selectedClient,
            compatibility: compatibility?.data ?? null
        });
    }, [format, previewMarkup, selectedClient, compatibility?.data]);

    const showCompatBadge =
        format === 'html' && compatibilityScore !== null;

    return (
        <Pane>
            <PaneHeader>
                <PaneHeaderGroup>
                    <PaneTitle>{t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:preview.title')}</PaneTitle>
                    {showCompatBadge && (
                        <CompatBadge
                            type="button"
                            status={statusFromScore(compatibilityScore as number)}
                            onClick={onShowCompatibility}
                            title={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:preview.showCompatibility')}
                            aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:preview.showCompatibility')}
                        >
                            {compatibilityScore}%
                        </CompatBadge>
                    )}
                </PaneHeaderGroup>
                <PreviewControls>
                    {format === 'html' && (
                        <PreviewControlGroup>
                            <PreviewSelect
                                id="cpx-preview-client"
                                value={selectedClientId}
                                onChange={(event) => setSelectedClientId(event.target.value)}
                                disabled={previewClients.length === 0}
                            >
                                {previewClients.map((client) => (
                                    <option key={client.id} value={client.id}>
                                        {client.label}
                                    </option>
                                ))}
                            </PreviewSelect>
                        </PreviewControlGroup>
                    )}
                    <PreviewControlGroup>
                        <DeviceToggle
                            $activeIndex={Math.max(0, DEVICE_ORDER.indexOf(device))}
                            role="group"
                            aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:preview.device')}
                        >
                            {DEVICE_ORDER.map((mode) => (
                                <DeviceToggleButton
                                    key={mode}
                                    type="button"
                                    onClick={() => setDevice(mode)}
                                    title={t(
                                        `Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:preview.device.${mode}`
                                    )}
                                    aria-label={t(
                                        `Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:preview.device.${mode}`
                                    )}
                                    aria-pressed={device === mode}
                                >
                                    <Icon icon={DEVICE_ICON[mode]} />
                                </DeviceToggleButton>
                            ))}
                        </DeviceToggle>
                    </PreviewControlGroup>
                </PreviewControls>
            </PaneHeader>
            {format === 'html' ? (
                <PreviewFrameStage>
                    <PreviewFrame
                        title={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:preview.htmlFrameTitle')}
                        srcDoc={transformedMarkup}
                        $maxWidth={DEVICE_MAX_WIDTH[device]}
                    />
                </PreviewFrameStage>
            ) : (
                <PreviewFrameStage>
                    <PlainPreview $maxWidth={DEVICE_MAX_WIDTH[device]}>
                        {previewPlaintext}
                    </PlainPreview>
                </PreviewFrameStage>
            )}
        </Pane>
    );
});

PreviewPane.displayName = 'PreviewPane';
