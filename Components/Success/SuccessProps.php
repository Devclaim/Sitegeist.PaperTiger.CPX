<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Success;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class SuccessProps
{
    private function __construct(
        public string $id,
        public string $message,
    ) {
    }

    public static function create(
        string $id,
        string $message,
    ): self {
        return new self(
            id: $id,
            message: $message,
        );
    }
}
