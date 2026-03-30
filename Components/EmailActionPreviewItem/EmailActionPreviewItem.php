<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\EmailActionPreviewItem;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class EmailActionPreviewItem implements _\ComponentInterface
{
    private function __construct(
        private string $label,
        private string $value,
    ) {
    }

    public static function create(
        string $label,
        string $value,
    ): self {
        return new self(
            label: $label,
            value: $value,
        );
    }

    public function render(): string
    {
        return '<dt class="papertiger-action-email__term">' . _\Util::escapeRenderValue($this->label) . '</dt><dd class="papertiger-action-email__description">' . _\Util::escapeRenderValue($this->value) . '</dd>';
    }
}
