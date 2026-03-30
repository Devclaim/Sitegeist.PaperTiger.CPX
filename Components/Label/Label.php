<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Label;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Label\LabelProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class Label implements _\ComponentInterface
{
    private function __construct(
        private LabelProps $label,
    ) {
    }

    public static function create(
        LabelProps $label,
    ): self {
        return new self(
            label: $label,
        );
    }

    public function render(): string
    {
        return '<label' . (($temp = $this->label->inputId) === null ? '' : ' for="' . _\Util::escapeAttributeValue($temp) . '"') . ' class="papertiger-field__label">' . (($temp = $this->label->label) === null ? '' : _\Util::escapeRenderValue($temp)) . '' . ($this->label->isRequired ? '<span class="papertiger-field__required">*</span>' : '') . '</label>';
    }
}
