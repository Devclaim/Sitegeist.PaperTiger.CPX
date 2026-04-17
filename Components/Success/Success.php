<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Success;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Success\SuccessProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class Success implements _\ComponentInterface
{
    private function __construct(
        private SuccessProps $success,
    ) {
    }

    public static function create(
        SuccessProps $success,
    ): self {
        return new self(
            success: $success,
        );
    }

    public function render(): string
    {
        return '<div id="' . _\Util::escapeAttributeValue($this->success->id) . '" class="papertiger-success" role="status">' . _\Util::escapeRenderValue($this->success->message) . '</div>';
    }
}
