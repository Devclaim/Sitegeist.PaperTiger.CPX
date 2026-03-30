<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Action\RedirectActionProps;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class RedirectActionProps
{
    private function __construct(
        public ?string $uri,
    ) {
    }

    public static function create(
        ?string $uri,
    ): self {
        return new self(
            uri: $uri,
        );
    }
}
