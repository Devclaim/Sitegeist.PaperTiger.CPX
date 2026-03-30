<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Action\Email;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\ComponentEngine\SlotComponent;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\ActionPreviewCard\ActionPreviewCard;
use Sitegeist\PaperTiger\CPX\Components\EmailActionPreview\EmailActionPreview;
use Sitegeist\PaperTiger\CPX\Components\EmailActionPreviewError\EmailActionPreviewError;
use Sitegeist\PaperTiger\CPX\Components\EmailActionPreviewItem\EmailActionPreviewItem;
use Sitegeist\PaperTiger\CPX\NodeTypes\Action\AbstractActionRenderer;

final class EmailRenderer extends AbstractActionRenderer
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $subject = $this->stringProperty($context->node, 'subject');
        $format = $this->stringProperty($context->node, 'format');
        $plaintext = $this->stringProperty($context->node, 'plaintext');
        $html = $this->stringProperty($context->node, 'html');
        $recipientAddress = $this->stringProperty($context->node, 'recipientAddress');
        $recipientName = $this->stringProperty($context->node, 'recipientName');
        $senderAddress = $this->stringProperty($context->node, 'senderAddress');
        $senderName = $this->stringProperty($context->node, 'senderName');
        $replyToAddress = $this->stringProperty($context->node, 'replyToAddress');
        $carbonCopyAddress = $this->stringProperty($context->node, 'carbonCopyAddress');
        $blindCarbonCopyAddress = $this->stringProperty($context->node, 'blindCarbonCopyAddress');

        $requiredFields = [];
        if ($recipientAddress === null) {
            $requiredFields[] = 'recipient-address';
        }
        if ($senderAddress === null) {
            $requiredFields[] = 'sender-address';
        }
        if ($plaintext === null && $html === null) {
            $requiredFields[] = 'text or HTML';
        }

        $items = array_values(array_filter([
            $this->emailPreviewItem('Subject', $subject),
            $format !== 'plaintext' ? $this->emailPreviewItem('Html', $html) : null,
            $format !== 'html' ? $this->emailPreviewItem('Plaintext', $plaintext) : null,
            $this->emailPreviewItem('Recipient', $this->joinParts($recipientName, $recipientAddress)),
            $this->emailPreviewItem('Sender', $this->joinParts($senderName, $senderAddress)),
            $this->emailPreviewItem('Reply to', $replyToAddress),
            $this->emailPreviewItem('Carbon copy', $carbonCopyAddress),
            $this->emailPreviewItem('Blind carbon copy', $blindCarbonCopyAddress),
        ]));

        return ActionPreviewCard::create(
            label: 'Email',
            content: EmailActionPreview::create(
                error: $requiredFields === []
                    ? null
                    : EmailActionPreviewError::create(
                        message: 'The following configuration is missing: ' . implode(', ', $requiredFields),
                    ),
                items: $items === []
                    ? null
                    : SlotComponent::list(...$items),
            ),
        );
    }

    private function emailPreviewItem(string $label, ?string $value): ?ComponentInterface
    {
        if ($value === null || $value === '') {
            return null;
        }

        return EmailActionPreviewItem::create(
            label: $label,
            value: $value,
        );
    }
}
