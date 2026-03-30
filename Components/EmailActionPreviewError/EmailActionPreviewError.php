<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\EmailActionPreviewError;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class EmailActionPreviewError implements _\ComponentInterface
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
        return '<p class="papertiger-action-email__error"><strong class="papertiger-action-email__error-text">' . _\Util::escapeRenderValue($this->message) . '</strong></p>';
    }
}
