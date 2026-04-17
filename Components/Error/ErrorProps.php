<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Error;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class ErrorProps
{
    private function __construct(
        public string $message,
    ) {
    }

    public static function create(
        string $message,
    ): self {
        return new self(
            message: $message,
        );
    }
}
