<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Message;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class MessageProps
{
    private function __construct(
        public string $id,
    ) {
    }

    public static function create(
        string $id,
    ): self {
        return new self(
            id: $id,
        );
    }
}
