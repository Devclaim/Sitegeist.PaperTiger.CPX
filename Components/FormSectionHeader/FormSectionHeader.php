<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\FormSectionHeader;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class FormSectionHeader implements _\ComponentInterface
{
    private function __construct(
        private string $number,
        private string $title,
    ) {
    }

    public static function create(
        string $number,
        string $title,
    ): self {
        return new self(
            number: $number,
            title: $title,
        );
    }

    public function render(): string
    {
        return '<div class="papertiger-form__header"><span class="paper-tiger-form__header-number">' . _\Util::escapeRenderValue($this->number) . '</span><span class="paper-tiger-form__header-title">' . _\Util::escapeRenderValue($this->title) . '</span></div>';
    }
}
