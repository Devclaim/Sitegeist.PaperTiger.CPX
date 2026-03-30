<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\HoneypotField;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class HoneypotFieldProps
{
    private function __construct(
        public string $name,
        public string $firstInputName,
        public string $secondInputName,
        public string $thirdInputName,
        public string $scriptTargetId,
        public string $timestampWithHmac,
        public ?string $style,
    ) {
    }

    public static function create(
        string $name,
        string $firstInputName,
        string $secondInputName,
        string $thirdInputName,
        string $scriptTargetId,
        string $timestampWithHmac,
        ?string $style,
    ): self {
        return new self(
            name: $name,
            firstInputName: $firstInputName,
            secondInputName: $secondInputName,
            thirdInputName: $thirdInputName,
            scriptTargetId: $scriptTargetId,
            timestampWithHmac: $timestampWithHmac,
            style: $style,
        );
    }
}
