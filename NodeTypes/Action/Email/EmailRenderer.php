<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Action\Email;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\ComponentEngine\SlotComponent;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\ActionPreviewCard\ActionPreviewCard;
use Sitegeist\PaperTiger\CPX\Components\EmailActionPreview\EmailActionPreview;
use Sitegeist\PaperTiger\CPX\Components\EmailActionPreviewError\EmailActionPreviewError;
use Sitegeist\PaperTiger\CPX\Components\EmailActionPreviewItem\EmailActionPreviewItem;

final class EmailRenderer implements ContentNodeRendererInterface
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $subject = $context->nodes->getStringValue($context->node, 'subject');
        $format = $context->nodes->getStringValue($context->node, 'format');
        $plaintext = $context->nodes->getStringValue($context->node, 'plaintext');
        $html = $context->nodes->getStringValue($context->node, 'html');
        $recipientAddress = $context->nodes->getStringValue($context->node, 'recipientAddress');
        $recipientName = $context->nodes->getStringValue($context->node, 'recipientName');
        $senderAddress = $context->nodes->getStringValue($context->node, 'senderAddress');
        $senderName = $context->nodes->getStringValue($context->node, 'senderName');
        $replyToAddress = $context->nodes->getStringValue($context->node, 'replyToAddress');
        $carbonCopyAddress = $context->nodes->getStringValue($context->node, 'carbonCopyAddress');
        $blindCarbonCopyAddress = $context->nodes->getStringValue($context->node, 'blindCarbonCopyAddress');

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

    private function joinParts(?string ...$parts): ?string
    {
        $parts = array_values(array_filter($parts, static fn (?string $part) => $part !== null && $part !== ''));

        if ($parts === []) {
            return null;
        }

        return implode(' ', $parts);
    }
}
