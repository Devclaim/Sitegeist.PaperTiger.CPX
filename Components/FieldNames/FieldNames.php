<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\FieldNames;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class FieldNames implements _\ComponentInterface
{
    private function __construct(
        private string $description,
        private ?_\ComponentInterface $content,
    ) {
    }

    public static function create(
        string $description,
        _\ComponentInterface|string|null $content,
    ): self {
        return new self(
            description: $description,
            content: is_string($content) ? _\StringComponent::fromString($content) : $content,
        );
    }

    public function render(): string
    {
        return '<div class="papertiger-fieldnames"><span class="papertiger-fieldnames__describtion">' . _\Util::escapeRenderValue($this->description) . '</span><div class="papertiger-fieldnames__content">' . (($temp = $this->content) === null ? '' : $temp->render()) . '</div></div>';
    }
}
