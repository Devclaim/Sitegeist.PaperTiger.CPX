<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\MessageActionPreview;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class MessageActionPreview implements _\ComponentInterface
{
    private function __construct(
        private string $message,
    ) {
    }

    public static function create(
        string $message,
    ): self {
        return new self(
            message: $message,
        );
    }

    public function render(): string
    {
        return '<div class="papertiger-action-message">' . _\Util::escapeRenderValue($this->message) . '</div>';
    }
}
