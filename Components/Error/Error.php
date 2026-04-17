<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Error;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Error\ErrorProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class Error implements _\ComponentInterface
{
    private function __construct(
        private ErrorProps $error,
    ) {
    }

    public static function create(
        ErrorProps $error,
    ): self {
        return new self(
            error: $error,
        );
    }

    public function render(): string
    {
        return '<p class="papertiger-error">' . _\Util::escapeRenderValue($this->error->message) . '</p>';
    }
}
