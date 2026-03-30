<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Action\EmailActionProps;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class EmailActionProps
{
    private function __construct(
        public ?string $subject,
        public ?string $format,
        public ?string $plaintext,
        public ?string $html,
        public ?string $recipientAddress,
        public ?string $recipientName,
        public ?string $senderAddress,
        public ?string $senderName,
        public ?string $replyToAddress,
        public ?string $carbonCopyAddress,
        public ?string $blindCarbonCopyAddress,
        public ?bool $attachUploads,
        public ?bool $testMode,
    ) {
    }

    public static function create(
        ?string $subject,
        ?string $format,
        ?string $plaintext,
        ?string $html,
        ?string $recipientAddress,
        ?string $recipientName,
        ?string $senderAddress,
        ?string $senderName,
        ?string $replyToAddress,
        ?string $carbonCopyAddress,
        ?string $blindCarbonCopyAddress,
        ?bool $attachUploads,
        ?bool $testMode,
    ): self {
        return new self(
            subject: $subject,
            format: $format,
            plaintext: $plaintext,
            html: $html,
            recipientAddress: $recipientAddress,
            recipientName: $recipientName,
            senderAddress: $senderAddress,
            senderName: $senderName,
            replyToAddress: $replyToAddress,
            carbonCopyAddress: $carbonCopyAddress,
            blindCarbonCopyAddress: $blindCarbonCopyAddress,
            attachUploads: $attachUploads,
            testMode: $testMode,
        );
    }
}
