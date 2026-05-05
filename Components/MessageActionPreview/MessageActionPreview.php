<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\MessageActionPreview;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class MessageActionPreview implements _\ComponentInterface
{
    private function __construct(
        private ?_\ComponentInterface $content,
        private string $formId,
    ) {
    }

    public static function create(
        _\ComponentInterface|string|null $content,
        string $formId,
    ): self {
        return new self(
            content: is_string($content) ? _\StringComponent::fromString($content) : $content,
            formId: $formId,
        );
    }

    public function render(): string
    {
        return '<div data-message-action-preview="' . _\Util::escapeAttributeValue($this->formId) . '" hidden>' . (($temp = $this->content) === null ? '' : $temp->render()) . '</div>';
    }
}
