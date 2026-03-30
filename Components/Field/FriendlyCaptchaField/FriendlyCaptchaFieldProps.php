<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\FriendlyCaptchaField;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class FriendlyCaptchaFieldProps
{
    private function __construct(
        public ?string $name,
    ) {
    }

    public static function create(
        ?string $name,
    ): self {
        return new self(
            name: $name,
        );
    }
}
