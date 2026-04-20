<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Form;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Form\FormMode;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class FormProps
{
    private function __construct(
        public string $id,
        public ?string $action,
        public ?string $method,
        public ?bool $noValidate,
        public ?FormMode $formMode,
    ) {
    }

    public static function create(
        string $id,
        ?string $action,
        ?string $method,
        ?bool $noValidate,
        ?FormMode $formMode,
    ): self {
        return new self(
            id: $id,
            action: $action,
            method: $method,
            noValidate: $noValidate,
            formMode: $formMode,
        );
    }
}
