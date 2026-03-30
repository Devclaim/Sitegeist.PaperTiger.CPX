<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\EmailActionPreview;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class EmailActionPreview implements _\ComponentInterface
{
    private function __construct(
        private ?_\ComponentInterface $error,
        private ?_\ComponentInterface $items,
    ) {
    }

    public static function create(
        _\ComponentInterface|string|null $error,
        _\ComponentInterface|string|null $items,
    ): self {
        return new self(
            error: is_string($error) ? _\StringComponent::fromString($error) : $error,
            items: is_string($items) ? _\StringComponent::fromString($items) : $items,
        );
    }

    public function render(): string
    {
        return '<div class="papertiger-action-email">' . (($temp = $this->error) === null ? '' : $temp->render()) . '<dl class="papertiger-action-email__list">' . (($temp = $this->items) === null ? '' : $temp->render()) . '</dl></div>';
    }
}
