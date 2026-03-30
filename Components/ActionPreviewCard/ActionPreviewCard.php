<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\ActionPreviewCard;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class ActionPreviewCard implements _\ComponentInterface
{
    private function __construct(
        private string $label,
        private ?_\ComponentInterface $content,
    ) {
    }

    public static function create(
        string $label,
        _\ComponentInterface|string|null $content,
    ): self {
        return new self(
            label: $label,
            content: is_string($content) ? _\StringComponent::fromString($content) : $content,
        );
    }

    public function render(): string
    {
        return '<div class="papertiger-actioncollection"><h2 class="papertiger-actioncollection__label">' . _\Util::escapeRenderValue($this->label) . '</h2><div class="papertiger-actioncollection__preview">' . (($temp = $this->content) === null ? '' : $temp->render()) . '</div></div>';
    }
}
