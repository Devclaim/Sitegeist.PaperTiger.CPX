import React from 'react';
import {Icon, IconButton, TextInput} from '@neos-project/react-ui-components';

import {SetFieldValue} from '../useEmailActionEditor';
import {useI18n} from '@sitegeist/papertiger-cpx-neos-bridge';
import {
    AddressArrow,
    AddressDivider,
    AddressEnvelope,
    AddressIcon,
    AddressInputSlot,
    AddressPopover,
    AddressPopoverField,
    AddressPopoverFieldLabel,
    AddressPopoverGrid,
    AddressPopoverHeader,
    AddressPopoverTitle,
    AddressPopoverWrapper
} from './EmailActionDialog.styles';

type Entry = Record<string, unknown>;

type AddressBarProps = {
    entry: Entry;
    onSetFieldValue: SetFieldValue;
    onFocusSubject: () => void;
    subjectInputRef: React.MutableRefObject<HTMLInputElement | null>;
};

const getStringValue = (value: unknown): string =>
    typeof value === 'string' ? value : '';

export const AddressBar: React.FC<AddressBarProps> = ({
    entry,
    onSetFieldValue,
    onFocusSubject,
    subjectInputRef
}) => {
    const t = useI18n();
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
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
            document.removeEventListener('keydown', handleKeyDown, true);
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
            <AddressInputSlot>
                <TextInput
                    value={getStringValue(entry.senderAddress)}
                    onChange={(value: string) => onSetFieldValue('senderAddress', value)}
                    placeholder={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.sender')}
                    aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.sender')}
                />
            </AddressInputSlot>
            <AddressArrow aria-hidden="true">→</AddressArrow>
            <AddressIcon aria-hidden="true">
                <Icon icon="envelope" />
            </AddressIcon>
            <AddressArrow aria-hidden="true">→</AddressArrow>
            <AddressInputSlot>
                <TextInput
                    value={getStringValue(entry.recipientAddress)}
                    onChange={(value: string) => onSetFieldValue('recipientAddress', value)}
                    placeholder={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.recipient')}
                    aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.recipient')}
                />
            </AddressInputSlot>
            <AddressDivider aria-hidden="true" />
            <AddressIcon aria-hidden="true">
                <Icon icon="heading" />
            </AddressIcon>
            <AddressInputSlot $grow={1.6} ref={subjectSlotRef}>
                <TextInput
                    value={getStringValue(entry.subject)}
                    onChange={(value: string) => onSetFieldValue('subject', value)}
                    onFocus={onFocusSubject}
                    placeholder={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:properties.subject')}
                    aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:properties.subject')}
                />
            </AddressInputSlot>
            <AddressPopoverWrapper ref={wrapperRef}>
                <IconButton
                    icon="ellipsis-h"
                    onClick={() => setPopoverOpen((open) => !open)}
                    title={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.advancedSenderData')}
                    aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.advancedSenderData')}
                    aria-expanded={popoverOpen}
                    isActive={popoverOpen}
                    style="lighter"
                    hoverStyle="brand"
                />
                {popoverOpen && (
                    <AddressPopover role="dialog" aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.advancedSenderData')}>
                        <AddressPopoverHeader>
                            <AddressPopoverTitle>
                                {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.advancedSenderData')}
                            </AddressPopoverTitle>
                            <IconButton
                                icon="times"
                                onClick={() => setPopoverOpen(false)}
                                title={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.close')}
                                aria-label={t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:editor.close')}
                                style="transparent"
                                hoverStyle="darken"
                                size="small"
                            />
                        </AddressPopoverHeader>
                        <AddressPopoverGrid>
                            <AddressPopoverField>
                                <AddressPopoverFieldLabel>
                                    {t('Sitegeist.PaperTiger.CPX:NodeTypes.Action.Email:properties.recipientName')}
                                </AddressPopoverFieldLabel>
                                <TextInput
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
                                    value={getStringValue(entry.senderName)}
                                    onChange={(value: string) =>
                                        onSetFieldValue('senderName', value)
                                    }
                                />
                            </AddressPopoverField>
                            <AddressPopoverField>
                                <AddressPopoverFieldLabel>Reply-To</AddressPopoverFieldLabel>
                                <TextInput
                                    value={getStringValue(entry.replyToAddress)}
                                    onChange={(value: string) =>
                                        onSetFieldValue('replyToAddress', value)
                                    }
                                />
                            </AddressPopoverField>
                            <AddressPopoverField>
                                <AddressPopoverFieldLabel>CC</AddressPopoverFieldLabel>
                                <TextInput
                                    value={getStringValue(entry.carbonCopyAddress)}
                                    onChange={(value: string) =>
                                        onSetFieldValue('carbonCopyAddress', value)
                                    }
                                />
                            </AddressPopoverField>
                            <AddressPopoverField>
                                <AddressPopoverFieldLabel>BCC</AddressPopoverFieldLabel>
                                <TextInput
                                    value={getStringValue(entry.blindCarbonCopyAddress)}
                                    onChange={(value: string) =>
                                        onSetFieldValue(
                                            'blindCarbonCopyAddress',
                                            value
                                        )
                                    }
                                />
                            </AddressPopoverField>
                        </AddressPopoverGrid>
                    </AddressPopover>
                )}
            </AddressPopoverWrapper>
        </AddressEnvelope>
    );
};
