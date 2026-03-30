<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\RedirectActionPreviewError;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class RedirectActionPreviewError implements _\ComponentInterface
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
        return '<div class="papertiger-action-redirect papertiger-action-redirect--error">' . _\Util::escapeRenderValue($this->message) . '</div>';
    }
}
