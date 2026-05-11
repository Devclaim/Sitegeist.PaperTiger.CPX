import styled from 'styled-components';

export const dialogStyles = `
    .papertiger-email-dialog[class*="dialog--jumbo"] [class*="dialog__contents"],
    .papertiger-email-dialog [class*="dialog--jumbo"] [class*="dialog__contents"] {
        width: 90vw;
        min-height: 90vh;
        height: 90vh;
        max-width: 90vw !important;
    }
    .papertiger-email-dialog [class*="dialog__body"] {
        height: 100%;
    }
`;

export const Container = styled.div`
    display: flex;
    flex-direction: column;
    gap: 16px;
    height: 100%;
    width: 100%;
    padding: 0 32px 0 32px;
`;

export const Header = styled.div`
    display: flex;
    flex-direction: column;
    gap: 6px;
`;

export const Title = styled.h2`
    margin: 0;
    font-size: 18px;
    line-height: 1.2;
`;

export const Description = styled.p`
    margin: 0;
    color: #666;
    font-size: 13px;
`;


export const ToolbarRow = styled.div`
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
`;


export const FormatToggle = styled.button<{ htmlActive: boolean }>`
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 6px;
    border: 0;
    background: #3f3f3f;
    color: white;
    cursor: pointer;
    overflow: hidden;
    transition: background-color 180ms ease, opacity 180ms ease;

    &::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 50%;
        background: #00adee;
        box-shadow: 0 8px 20px rgba(11, 111, 219, 0.28);
        transform: translateX(${({htmlActive}) => htmlActive ? '100%' : '0%'});
        transition: transform 220ms ease;
    }
`;

export const FormatToggleSide = styled.span`
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-width: 0;
    flex: 1;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 700;
    color: #ffffff;
    transition: color 180ms ease;
    white-space: nowrap;
`;


export const AddressEnvelope = styled.div`
    display: inline-flex;
    align-items: center;
    gap: 6px;
    flex: 1 1 auto;
    min-width: 0;
    margin-left: auto;
`;

export const AddressInputSlot = styled.div<{ $grow?: number }>`
    flex: ${({$grow}) => $grow ?? 1} 1 0;
    min-width: 0;
`;

export const AddressDivider = styled.span`
    flex-shrink: 0;
    width: 1px;
    height: 22px;
    background: #ddd;
    margin: 0 4px;
`;

export const AddressArrow = styled.span`
    flex-shrink: 0;
    color: #888;
    font-size: 14px;
    line-height: 1;
    user-select: none;
`;

export const AddressIcon = styled.span`
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #f1f1f1;
    color: #555;
    font-size: 12px;
`;

export const AddressPopoverWrapper = styled.div`
    position: relative;
    flex-shrink: 0;
    margin-left: 4px;
`;

export const AddressPopover = styled.div`
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    z-index: 50;
    width: min(440px, 90vw);
    padding: 14px;
    border: 1px solid #d7d7d7;
    border-radius: 6px;
    background: #fff;
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18);
`;

export const AddressPopoverHeader = styled.div`
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
`;

export const AddressPopoverTitle = styled.h4`
    margin: 0;
    font-size: 12px;
    font-weight: 700;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 0.04em;
`;

export const AddressPopoverGrid = styled.div`
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
`;

export const AddressPopoverField = styled.label`
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
`;

export const AddressPopoverFieldLabel = styled.span`
    font-size: 11px;
    font-weight: 700;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.04em;
`;



export const EditorLayout = styled.div`
    min-height: 0;
    flex: 1;
    height: 100%;
    contain: layout;

    @media (max-width: 1100px) {
        display: block;
    }
`;

export const Pane = styled.div`
    display: flex;
    flex-direction: column;
    height: 100%;
    min-height: 0;
    border-radius: 6px;
    overflow: hidden;
    background: #3f3f3f;
`;

export const ResizeHandleVisual = styled.div`
    position: relative;
    width: 16px;
    height: 100%;
    cursor: col-resize;
    background: transparent;

    &::before {
        content: '';
        position: absolute;
        top: 6px;
        bottom: 6px;
        left: 50%;
        width: 1px;
        background: #d7d7d7;
        transform: translateX(-50%);
    }

    &::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 6px;
        height: 42px;
        border-radius: 999px;
        background: #b7b7b7;
        transform: translate(-50%, -50%);
        box-shadow: 0 0 0 4px #f5f5f5;
    }

    @media (max-width: 1100px) {
        display: none;
    }
`;

export const PaneHeader = styled.div`
    display: flex;
    flex-wrap: wrap;
    flex-shrink: 0;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 10px 12px;
    background: #323232;
    min-height: 51px;
`;

export const PaneTitle = styled.div`
    font-size: 12px;
    font-weight: 700;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.04em;
`;


export const EditorSurface = styled.div`
    flex: 1;
    min-height: 0;
    min-width: 0;
    overflow: hidden;
    display: flex;
    flex-direction: column;

    .cm-editor {
        height: 100%;
        font-size: 13px;
    }
    .cm-editor.cm-focused {
        outline: none;
    }
    .cm-scroller {
        overflow: auto;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    }
    .cm-content {
        color: #d4d4d4;
        caret-color: #äafad;
    }
    .cm-gutters {
        background-color: #1e1e1e;
        color: #858585;
        border-right: 1px solid #2d2d2d;
    }
    .cm-activeLineGutter,
    .cm-activeLine {
        background-color: #2a2d2e;
    }
`;

export const PlaintextEditor = styled.textarea`
    flex: 1;
    width: 100%;
    min-height: 0;
    padding: 14px;
    border: 0;
    resize: none;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    line-height: 1.5;
    font-size: 20px !important;
    background: #3f3f3f;
    color: white;
    outline: none;
    overflow: auto;
`;

export const PreviewFrameStage = styled.div`
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: stretch;
    min-height: 0;
    overflow: auto;
    background: repeating-linear-gradient(
        -55deg,
        #222,
        #222 24px,
        #333 24px,
        #333 36px
    );
`;

export const PreviewFrame = styled.iframe<{ $maxWidth?: string }>`
    width: 100%;
    max-width: ${({$maxWidth}) => $maxWidth ?? 'none'};
    height: auto;
    background: #ececec;
    border: 0;
`;


export const SubjectPreview = styled.div`
    margin: 0;
    font-size: 11px;
    color: #7a7a7a;
`;

export const PaneHeaderGroup = styled.div`
    display: inline-flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
`;

export const CompatBadge = styled.button<{ status: CompatStatus }>`
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 56px;
    height: 30px;
    padding: 0 12px;
    border: 1px solid
        ${({ status }) =>
            status === 'unsupported' ? '#cb2431' :
            status === 'partial' ? '#dbab09' :
            status === 'ok' ? '#2ea44f' :
            '#9a9a9a'};
    border-radius: 999px;
    background:
        ${({ status }) =>
            status === 'unsupported' ? '#fdebec' :
            status === 'partial' ? '#fff8db' :
            status === 'ok' ? '#eaf7ee' :
            '#f2f2f2'};
    color: black;
    font-family: inherit;
    font-size: 13px;
    font-weight: 800;
    line-height: 1;
    cursor: pointer;
    transition: background 150ms ease, transform 100ms ease;

    &:hover {
        background:
            ${({ status }) =>
                status === 'unsupported' ? '#f8d7da' :
                status === 'partial' ? '#fff1b8' :
                status === 'ok' ? '#d4edda' :
                '#e5e5e5'};
    }

    &:active {
        transform: scale(0.97);
    }

    &:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 173, 238, 0.25);
    }
`;

export const BackToEditorButton = styled.button`
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: none;
    border-radius: 4px;
    padding: 4px 10px;
    background: #3f3f3f;
    color: white;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: border-color 150ms ease, color 150ms ease;

    &:hover {
        color: #00adee;
    }

    &:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(0, 173, 238, 0.25);
    }
`;

export const PreviewControls = styled.div`
    display: flex;
    flex-shrink: 0;
    flex-wrap: wrap;
    align-items: center;
    gap: 14px;
`;

export const PreviewControlGroup = styled.div`
    display: inline-flex;
    align-items: center;
    gap: 8px;
`;

export const PreviewControlLabel = styled.label`
    font-size: 11px;
    font-weight: 700;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.04em;
`;

export const PreviewSelect = styled.select`
    min-width: 200px;
    padding: 4px 8px;
    background: #3f3f3f;
    border: none;
    color: white;
    font-size: 12px;

    &:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
`;


export const DeviceToggle = styled.div<{ $activeIndex: number }>`
    position: relative;
    display: inline-flex;
    align-items: center;
    height: 28px;
    padding: 0;
    border: 0;
    background: #3f3f3f;
    overflow: hidden;

    &::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: calc(100% / 3);
        background: #00adee;
        box-shadow: 0 4px 12px rgba(0, 173, 238, 0.28);
        transform: translateX(${({$activeIndex}) => $activeIndex * 100}%);
        transition: transform 220ms ease;
    }
`;

export const DeviceToggleButton = styled.button`
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 100%;
    padding: 0;
    border: 0;
    background: transparent;
    color: #fff;
    font-size: 13px;
    cursor: pointer;
    transition: color 180ms ease;

    &:hover {
        color: #fff;
    }

    &:focus {
        outline: none;
    }
`;


export const TokenBar = styled.div`
    display: flex;
    flex-shrink: 0;
    align-items: center;
    gap: 6px;
    padding: 10px 12px;
    background: #323232;
    min-height: 51px;
    overflow-y: auto;
`;

export const TokenButton = styled.button`
    display: inline-flex;
    align-items: center;
    height: 22px;
    padding: 4px 10px;
    background: #3f3f3f;
    color: white;
    font-family: inherit;
    font-size: 12px;
    font-weight: 600;
    line-height: 1;
    cursor: pointer;
    white-space: nowrap;
    border: none;
    transition: color 120ms ease, background 120ms ease;

    &:hover {
        color: #00adee;
    }
`;

export const PlainPreview = styled.pre<{ $maxWidth?: string }>`
    margin: 0;
    width: 100%;
    max-width: ${({$maxWidth}) => $maxWidth ?? 'none'};
    height: 100%;
    padding: 18px;
    overflow: auto;
    white-space: pre-wrap;
    word-break: break-word;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: 20px;
    line-height: 1.5;
    background: #3f3f3f;
`;

type CompatStatus = 'ok' | 'partial' | 'unsupported' | 'unknown';

export const CompatPane = styled.section`
    display: flex;
    flex-direction: column;
    flex: 1;
    min-height: 0;
    background: #3f3f3f;
    overflow: hidden;
`;

export const CompatPaneHeader = styled.div`
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 10px 12px;
    background: #323232;
`;


export const CompatPaneHint = styled.div`
    font-size: 11px;
    color: #8a8a8a;
`;

export const CompatPaneActions = styled.div`
    display: flex;
    align-items: center;
    gap: 10px;
`;

export const CompatRefreshButton = styled.button`
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: none;
    padding: 4px 10px;
    background: #3f3f3f;
    color: white;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    transition: border-color 150ms ease, color 150ms ease;

    &:hover:not(:disabled) {
        color: #00adee;
    }

    &:disabled {
        opacity: 0.6;
        cursor: progress;
    }
`;

export const CompatErrorBox = styled.div`
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
    padding: 12px 14px;
    border: 1px solid #f1c4be;
    border-radius: 6px;
    background: #fdecea;
    color: #8a1f1f;
    font-size: 12px;
`;

export const CompatErrorActions = styled.div`
    display: flex;
    gap: 8px;
`;

export const CompatLoading = styled.div`
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    color: #555;
    font-size: 12px;
`;

export const CompatPaneBody = styled.div`
    display: flex;
    flex-direction: column;
    flex: 1;
    min-height: 0;
    gap: 14px;
    padding: 12px;
    overflow: auto;
`;


export const CompatFamilyCaret = styled.span<{ $expanded: boolean }>`
    display: inline-block;
    flex-shrink: 0;
    width: 7px;
    height: 7px;
    border-right: 2px solid #555;
    border-bottom: 2px solid #555;
    transform: ${({$expanded}) => ($expanded ? 'rotate(45deg)' : 'rotate(-45deg)')};
    transition: transform 150ms ease;
`;


export const CompatStatusDot = styled.span<{ status: CompatStatus }>`
    flex-shrink: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: ${({status}) =>
        status === 'unsupported' ? '#cb2431' :
        status === 'partial' ? '#dbab09' :
        status === 'ok' ? '#2ea44f' :
        '#9a9a9a'};
`;


export const CompatSectionHeading = styled.h4`
    margin: 0;
    padding-bottom: 4px;
    border-bottom: 1px solid #8a8a8a;
    font-size: 11px;
    font-weight: 700;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.04em;
`;


export const CompatEmpty = styled.div`
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 18px;
    color: #777;
    font-size: 12px;
`;

export const CompatLegend = styled.div`
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 11px;
    color: #555;
`;

export const CompatLegendItem = styled.span`
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: white;
`;

// ----- Slug-based overview row -----

export const CompatOverviewRow = styled.div`
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 24px;
    padding: 6px 0;
    font-size: 24px;
`;

export const CompatOverviewItem = styled.span<{ status: CompatStatus }>`
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: white;

    &::before {
        content: '';
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: ${({status}) =>
            status === 'unsupported' ? '#cb2431' :
            status === 'partial' ? '#dbab09' :
            status === 'ok' ? '#2ea44f' :
            '#9a9a9a'};
    }
`;

export const CompatOverviewValue = styled.strong`
    font-weight: 700;
    color: white;
`;

// ----- Slug-based feature rows -----

export const CompatFeatureList = styled.ul`
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
`;

export const CompatFeatureRow = styled.li`
    display: flex;
    flex-direction: column;
`;

export const CompatFeatureHeader = styled.button`
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 8px 10px;
    border: none;
    background: #242424;
    color: white;
    cursor: pointer;
    text-align: left;
    font-family: inherit;
    font-size: 12px;
    transition: background 120ms ease;

    &:hover {
        color: #00adee;
        background: #2d2c2c;
    }
`;

export const CompatFeatureLabel = styled.span`
    flex: 1;
    min-width: 0;
    display: inline-flex;
    align-items: baseline;
    gap: 8px;
    overflow: hidden;
`;

export const CompatFeatureTitleText = styled.span`
    font-weight: 700;
    color: inherit;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
`;

export const CompatFeatureSlugText = styled.code`
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: 11px;
    font-weight: 400;
    color: #777;
    background: rgba(0, 0, 0, 0.04);
    padding: 1px 5px;
    border-radius: 3px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    flex-shrink: 0;
`;

export const CompatFeatureBar = styled.div`
    display: flex;
    flex-shrink: 0;
    width: 50%;
    height: 16px;
    border-radius: 999px;
    overflow: hidden;
`;

export const CompatFeatureBarSegment = styled.div<{
    status: CompatStatus;
    $pct: number;
}>`
    width: ${({$pct}) => $pct}%;
    background: ${({status}) =>
        status === 'unsupported' ? '#cb2431' :
        status === 'partial' ? '#dbab09' :
        status === 'ok' ? '#2ea44f' :
        '#9a9a9a'};
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    white-space: nowrap;
    transition: width 200ms ease;
`;

export const CompatFeatureDetails = styled.div`
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 12px 14px 14px 14px;
    margin: -2px 6px 0 6px;
    border: 1px solid #e2e2e2;
    border-top: none;
    border-radius: 0 0 6px 6px;
    background: #fafafa;
    font-size: 12px;
    color: #333;
`;

export const CompatFeatureDescription = styled.p`
    margin: 0;
    color: #444;
    line-height: 1.5;
`;

export const CompatDetailHeading = styled.div`
    margin-top: 4px;
    font-size: 11px;
    font-weight: 700;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.04em;
`;

export const CompatVersionList = styled.div`
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
`;

export const CompatVersionChip = styled.span<{ status: 'partial' | 'unsupported' }>`
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    border-radius: 999px;
    font-size: 11px;
    color: #444;
    border: 1px solid
        ${({status}) => (status === 'unsupported' ? '#f1c4be' : '#f3dca6')};
    background: ${({status}) =>
        status === 'unsupported' ? '#fdecea' : '#fef7e6'};
`;

export const CompatVersionNoteRef = styled.sup`
    color: #b08300;
    font-weight: 700;
    margin-left: 2px;
`;

export const CompatNotesList = styled.ol`
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
`;

export const CompatNoteItem = styled.li`
    display: flex;
    align-items: flex-start;
    gap: 8px;
    font-size: 11px;
    color: #555;
`;

export const CompatNoteNum = styled.span`
    flex-shrink: 0;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #b08300;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
`;

export const CompatReferenceLink = styled.a`
    align-self: flex-start;
    color: #00adee;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;

    &:hover {
        text-decoration: underline;
    }
`;
