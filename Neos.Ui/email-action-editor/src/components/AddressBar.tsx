import React from 'react';
import {CheckBox, Icon, IconButton, Label, TextInput, Tooltip} from '@neos-project/react-ui-components';

import {SetFieldValue} from '../useEmailActionEditor';
import {useI18n} from '@sitegeist/papertiger-cpx-neos-bridge';
import {useDialogDirtyContext} from '../dialogDirtyContext';
import {
    AddressArrow,
    AddressDivider,
    AddressEnvelope,
    AddressIcon,
    AddressInputSlot,
    AddressPopover,
    CheckboxLabel,
    AddressPopoverField,
    AddressPopoverFieldLabel,
    AddressPopoverGrid,
    AddressPopoverHeader,
    AddressPopoverToggle,
    AddressPopoverTitle,
    AddressPopoverWrapper
} from './EmailActionDialog.styles';

type Entry = Record<string, unknown>;

type AddressBarProps = {
    entry: Entry;
    fieldWarnings: Record<string, string | undefined>;
    onSetFieldValue: SetFieldValue;
    onFocusSubject: () => void;
    subjectInputRef: React.MutableRefObject<HTMLInputElement | null>;
};

const getStringValue = (value: unknown): string =>
    typeof value === 'string' ? value : '';
const getInputClassName = (isDirty: boolean, isInvalid: boolean): string | undefined => {
    const classes = [
        isDirty ? 'papertiger-dirty-input' : '',
        isInvalid ? 'papertiger-invalid-input' : ''
    ].filter(Boolean);
    return classes.length ? classes.join(' ') : undefined;
};

export const AddressBar: React.FC<AddressBarProps> = ({
    entry,
    fieldWarnings,
    onSetFieldValue,
    onFocusSubject,
    subjectInputRef
}) => {
    const t = useI18n();
    const {dirtyFields, neosFieldHighlights} = useDialogDirtyContext();
    const isPopoverDirty =
        dirtyFields.recipientName === true ||
        dirtyFields.senderName === true ||
        dirtyFields.replyToAddress === true ||
        dirtyFields.carbonCopyAddress === true ||
        dirtyFields.blindCarbonCopyAddress === true ||
        neosFieldHighlights.recipientName === true ||
        neosFieldHighlights.senderName === true ||
        neosFieldHighlights.replyToAddress === true ||
        neosFieldHighlights.carbonCopyAddress === true ||
        neosFieldHighlights.blindCarbonCopyAddress === true;
    const [popoverOpen, setPopoverOpen] = React.useState(false);
    const wrapperRef = React.useRef<HTMLDivElement | null>(null);
    const subjectSlotRef = React.useRef<HTMLDivElement | null>(null);

    React.useEffect(() => {
        if (!popoverOpen) {
            return;
        }

        const handleClickOutside = (event: MouseEvent) => {
            if (
                wrapperRef.current &&
                !wrapperRef.current.contains(event.target as Node)
            ) {
                setPopoverOpen(false);
            }
        };

        const handleKeyDown = (event: KeyboardEvent) => {
            if (event.key !== 'Escape') {
                return;
            }
            event.stopPropagation();
            event.stopImmediatePropagation();
            event.preventDefault();
            setPopoverOpen(false);
        };

        document.addEventListener('mousedown', handleClickOutside);
        document.addEventListener('keydown', handleKeyDown, true);
        const iframeFocusWatcher = window.setInterval(() => {
            const activeElement = document.activeElement;
            if (activeElement && activeElement.tagName === 'IFRAME') {
                setPopoverOpen(false);
            }
        }, 120);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
            document.removeEventListener('keydown', handleKeyDown, true);
            window.clearInterval(iframeFocusWatcher);
        };
    }, [popoverOpen]);

    React.useEffect(() => {
        const slot = subjectSlotRef.current;
        if (!slot) {
            return;
        }
        const input = slot.querySelector('input');
        subjectInputRef.current = input;
    });

    return (
        <AddressEnvelope>
            <AddressIcon aria-hidden="true">
                <Icon icon="heading" />
            </AddressIcon>
            <AddressInputSlot $grow={1.6} $dirty={dirtyFields.subject === true || neosFieldHighlights.subject === true} ref={subjectSlotRef}>
                <TextInput
                    value={getStringValue(entry.subject)}
                    onChange={(value: string) => onSetFieldValue('subject', value)}
                    onFocus={onFocusSubject}
                    placeholder={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:properties.subject')}
                    aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:properties.subject')}
                />
            </AddressInputSlot>
            <AddressDivider aria-hidden="true" />
            <AddressInputSlot $dirty={dirtyFields.senderAddress === true || neosFieldHighlights.senderAddress === true}>
                <TextInput
                    className={getInputClassName(
                        dirtyFields.senderAddress === true || neosFieldHighlights.senderAddress === true,
                        Boolean(fieldWarnings.senderAddress)
                    )}
                    value={getStringValue(entry.senderAddress)}
                    onChange={(value: string) => onSetFieldValue('senderAddress', value)}
                    placeholder={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.sender')}
                    aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.sender')}
                />
                {fieldWarnings.senderAddress ? (
                    <span className="papertiger-inline-warning">
                        <Tooltip renderInline asWarning>{fieldWarnings.senderAddress}</Tooltip>
                    </span>
                ) : null}
            </AddressInputSlot>
            <AddressArrow aria-hidden="true">→</AddressArrow>
            <AddressIcon aria-hidden="true">
                <Icon icon="envelope" />
            </AddressIcon>
            <AddressArrow aria-hidden="true">→</AddressArrow>
            <AddressInputSlot $dirty={dirtyFields.recipientAddress === true || neosFieldHighlights.recipientAddress === true}>
                <TextInput
                    className={getInputClassName(
                        dirtyFields.recipientAddress === true || neosFieldHighlights.recipientAddress === true,
                        Boolean(fieldWarnings.recipientAddress)
                    )}
                    value={getStringValue(entry.recipientAddress)}
                    onChange={(value: string) => onSetFieldValue('recipientAddress', value)}
                    placeholder={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.recipient')}
                    aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.recipient')}
                />
                {fieldWarnings.recipientAddress ? (
                    <span className="papertiger-inline-warning">
                        <Tooltip renderInline asWarning>{fieldWarnings.recipientAddress}</Tooltip>
                    </span>
                ) : null}
            </AddressInputSlot>
            <AddressPopoverWrapper ref={wrapperRef}>
                <AddressPopoverToggle $dirty={isPopoverDirty && !popoverOpen}>
                    <IconButton
                        icon="ellipsis-h"
                        onClick={() => setPopoverOpen((open) => !open)}
                        title={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.advancedSenderData')}
                        aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.advancedSenderData')}
                        aria-expanded={popoverOpen}
                        isActive={false}
                        style="lighter"
                        hoverStyle="brand"
                    />
                </AddressPopoverToggle>
                {popoverOpen && (
                    <AddressPopover role="dialog" aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.advancedSenderData')}>
                        <AddressPopoverHeader>
                            <AddressPopoverTitle>
                                {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.advancedSenderData')}
                            </AddressPopoverTitle>
                        </AddressPopoverHeader>
                        <AddressPopoverGrid>
                            <AddressPopoverField>
                                <AddressPopoverFieldLabel>
                                    {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:properties.recipientName')}
                                </AddressPopoverFieldLabel>
                                <TextInput
                                    className={getInputClassName(
                                        dirtyFields.recipientName === true || neosFieldHighlights.recipientName === true,
                                        false
                                    )}
                                    value={getStringValue(entry.recipientName)}
                                    onChange={(value: string) =>
                                        onSetFieldValue('recipientName', value)
                                    }
                                />
                            </AddressPopoverField>
                            <AddressPopoverField>
                                <AddressPopoverFieldLabel>
                                    {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:properties.senderName')}
                                </AddressPopoverFieldLabel>
                                <TextInput
                                    className={getInputClassName(
                                        dirtyFields.senderName === true || neosFieldHighlights.senderName === true,
                                        false
                                    )}
                                    value={getStringValue(entry.senderName)}
                                    onChange={(value: string) =>
                                        onSetFieldValue('senderName', value)
                                    }
                                />
                            </AddressPopoverField>
                            <AddressPopoverField>
                                <AddressPopoverFieldLabel>Reply-To</AddressPopoverFieldLabel>
                                <TextInput
                                    className={getInputClassName(
                                        dirtyFields.replyToAddress === true || neosFieldHighlights.replyToAddress === true,
                                        Boolean(fieldWarnings.replyToAddress)
                                    )}
                                    value={getStringValue(entry.replyToAddress)}
                                    onChange={(value: string) =>
                                        onSetFieldValue('replyToAddress', value)
                                    }
                                />
                                {fieldWarnings.replyToAddress ? (
                                    <span className="papertiger-inline-warning">
                                        <Tooltip renderInline asWarning>{fieldWarnings.replyToAddress}</Tooltip>
                                    </span>
                                ) : null}
                            </AddressPopoverField>
                            <AddressPopoverField>
                                <AddressPopoverFieldLabel>CC</AddressPopoverFieldLabel>
                                <TextInput
                                    className={getInputClassName(
                                        dirtyFields.carbonCopyAddress === true || neosFieldHighlights.carbonCopyAddress === true,
                                        Boolean(fieldWarnings.carbonCopyAddress)
                                    )}
                                    value={getStringValue(entry.carbonCopyAddress)}
                                    onChange={(value: string) =>
                                        onSetFieldValue('carbonCopyAddress', value)
                                    }
                                />
                                {fieldWarnings.carbonCopyAddress ? (
                                    <span className="papertiger-inline-warning">
                                        <Tooltip renderInline asWarning>{fieldWarnings.carbonCopyAddress}</Tooltip>
                                    </span>
                                ) : null}
                            </AddressPopoverField>
                            <AddressPopoverField>
                                <AddressPopoverFieldLabel>BCC</AddressPopoverFieldLabel>
                                <TextInput
                                    className={getInputClassName(
                                        dirtyFields.blindCarbonCopyAddress === true || neosFieldHighlights.blindCarbonCopyAddress === true,
                                        Boolean(fieldWarnings.blindCarbonCopyAddress)
                                    )}
                                    value={getStringValue(entry.blindCarbonCopyAddress)}
                                    onChange={(value: string) =>
                                        onSetFieldValue(
                                            'blindCarbonCopyAddress',
                                            value
                                        )
                                    }
                                />
                                {fieldWarnings.blindCarbonCopyAddress ? (
                                    <span className="papertiger-inline-warning">
                                        <Tooltip renderInline asWarning>{fieldWarnings.blindCarbonCopyAddress}</Tooltip>
                                    </span>
                                ) : null}
                            </AddressPopoverField>
                            <AddressPopoverField>
                                <CheckboxLabel>
                                    <CheckBox
                                    
                                        isChecked={Boolean(entry.attachUploads)}
                                        onChange={(isChecked: boolean) =>
                                            onSetFieldValue('attachUploads', isChecked)
                                        }
                                    />
                                    {t(
                                        'properties.attachUploads',
                                        'Attach uploads',
                                        {},
                                        'Sitegeist.PaperTiger.CPX',
                                        'NodeTypes.Action.Email'
                                    )}
                                </CheckboxLabel>
                            </AddressPopoverField>
                        </AddressPopoverGrid>
                    </AddressPopover>
                )}
            </AddressPopoverWrapper>
        </AddressEnvelope>
    );
};
