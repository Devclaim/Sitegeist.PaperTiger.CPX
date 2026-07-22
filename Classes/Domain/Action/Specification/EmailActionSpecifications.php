<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Action\Specification;

use Neos\Flow\Annotations as Flow;

/**
 * Mirrors the current Sitegeist.PaperTiger.CPX:Action.Email child node.
 *
 * The properties intentionally match the existing child node names so the
 * migration path from child nodes to a form property stays straightforward.
 */
#[Flow\Proxy(false)]
final readonly class EmailActionSpecifications implements ActionSpecificationInterface
{
    public function __construct(
        public ?string $subject = null,
        public string $format = 'plaintext',
        public ?string $plaintext = null,
        public ?string $html = null,
        public ?string $recipientAddress = null,
        public ?string $recipientName = null,
        public ?string $senderAddress = null,
        public ?string $senderName = null,
        public ?string $replyToAddress = null,
        public ?string $carbonCopyAddress = null,
        public ?string $blindCarbonCopyAddress = null,
        public bool $attachUploads = false,
    ) {
    }

    public function type(): string
    {
        return 'email';
    }

    /**
     * @param array<string, mixed> $values
     */
    public static function fromArray(array $values): self
    {
        return new self(
            subject: is_string($values['subject'] ?? null) ? $values['subject'] : null,
            format: is_string($values['format'] ?? null) ? $values['format'] : 'plaintext',
            plaintext: is_string($values['plaintext'] ?? null) ? $values['plaintext'] : null,
            html: is_string($values['html'] ?? null) ? $values['html'] : null,
            recipientAddress: is_string($values['recipientAddress'] ?? null) ? $values['recipientAddress'] : null,
            recipientName: is_string($values['recipientName'] ?? null) ? $values['recipientName'] : null,
            senderAddress: is_string($values['senderAddress'] ?? null) ? $values['senderAddress'] : null,
            senderName: is_string($values['senderName'] ?? null) ? $values['senderName'] : null,
            replyToAddress: is_string($values['replyToAddress'] ?? null) ? $values['replyToAddress'] : null,
            carbonCopyAddress: is_string($values['carbonCopyAddress'] ?? null) ? $values['carbonCopyAddress'] : null,
            blindCarbonCopyAddress: is_string($values['blindCarbonCopyAddress'] ?? null) ? $values['blindCarbonCopyAddress'] : null,
            attachUploads: (bool)($values['attachUploads'] ?? false),
        );
    }

    /**
     * @return array{
     *   type: string,
     *   subject: ?string,
     *   format: string,
     *   plaintext: ?string,
     *   html: ?string,
     *   recipientAddress: ?string,
     *   recipientName: ?string,
     *   senderAddress: ?string,
     *   senderName: ?string,
     *   replyToAddress: ?string,
     *   carbonCopyAddress: ?string,
     *   blindCarbonCopyAddress: ?string,
     *   attachUploads: bool
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type(),
            'subject' => $this->subject,
            'format' => $this->format,
            'plaintext' => $this->plaintext,
            'html' => $this->html,
            'recipientAddress' => $this->recipientAddress,
            'recipientName' => $this->recipientName,
            'senderAddress' => $this->senderAddress,
            'senderName' => $this->senderName,
            'replyToAddress' => $this->replyToAddress,
            'carbonCopyAddress' => $this->carbonCopyAddress,
            'blindCarbonCopyAddress' => $this->blindCarbonCopyAddress,
            'attachUploads' => $this->attachUploads,
        ];
    }
}
