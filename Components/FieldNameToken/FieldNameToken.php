<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\FieldNameToken;

use PackageFactory\ComponentEngine as _;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class FieldNameToken implements _\ComponentInterface
{
    private function __construct(
        private string $token,
        private string $buttonTitle,
    ) {
    }

    public static function create(
        string $token,
        string $buttonTitle,
    ): self {
        return new self(
            token: $token,
            buttonTitle: $buttonTitle,
        );
    }

    public function render(): string
    {
        return '<span class="papertiger-fieldnames__item"><span class="papertiger-fieldnames__text">' . _\Util::escapeRenderValue($this->token) . '</span><button type="button" class="papertiger-fieldnames__button" title="' . _\Util::escapeAttributeValue($this->buttonTitle) . '">' . _\Util::escapeRenderValue($this->token) . '</button></span>';
    }
}
