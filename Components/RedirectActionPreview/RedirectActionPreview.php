<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\RedirectActionPreview;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class RedirectActionPreview implements _\ComponentInterface
{
    private function __construct(
        private string $uri,
    ) {
    }

    public static function create(
        string $uri,
    ): self {
        return new self(
            uri: $uri,
        );
    }

    public function render(): string
    {
        return '<a class="papertiger-action-redirect" href="' . _\Util::escapeAttributeValue($this->uri) . '" target="_blank">' . _\Util::escapeRenderValue($this->uri) . '</a>';
    }
}
