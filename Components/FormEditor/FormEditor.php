<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\FormEditor;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class FormEditor implements _\ComponentInterface
{
    private function __construct(
        private ?_\ComponentInterface $assets,
        private ?_\ComponentInterface $fields,
        private ?_\ComponentInterface $actions,
    ) {
    }

    public static function create(
        _\ComponentInterface|string|null $assets,
        _\ComponentInterface|string|null $fields,
        _\ComponentInterface|string|null $actions,
    ): self {
        return new self(
            assets: is_string($assets) ? _\StringComponent::fromString($assets) : $assets,
            fields: is_string($fields) ? _\StringComponent::fromString($fields) : $fields,
            actions: is_string($actions) ? _\StringComponent::fromString($actions) : $actions,
        );
    }

    public function render(): string
    {
        return '<div class="papertiger-form">' . (($temp = $this->assets) === null ? '' : $temp->render()) . '' . (($temp = $this->fields) === null ? '' : $temp->render()) . '' . (($temp = $this->actions) === null ? '' : $temp->render()) . '</div>';
    }
}
