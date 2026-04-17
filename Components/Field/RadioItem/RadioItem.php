<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\RadioItem;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Field\RadioItem\RadioItemProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class RadioItem implements _\ComponentInterface
{
    private function __construct(
        private RadioItemProps $option,
    ) {
    }

    public static function create(
        RadioItemProps $option,
    ): self {
        return new self(
            option: $option,
        );
    }

    public function render(): string
    {
        return '<label class="papertiger-radio-item"><input type="radio" name="' . _\Util::escapeAttributeValue($this->option->name) . '" value="' . _\Util::escapeAttributeValue($this->option->value) . '"' . (($temp = $this->option->isChecked) === null ? '' : ($temp ? ' checked' : '')) . '' . (($temp = $this->option->isRequired) === null ? '' : ($temp ? ' required' : '')) . ' class="papertiger-radio-item__input" data-fieldtype="input"' . ($this->option->customErrorMessageEnabled ? (($temp = $this->option->customErrorMessage) === null ? '' : ' data-custom-error-message="' . _\Util::escapeAttributeValue($temp) . '"') : '') . ' oninvalid="this.setCustomValidity(this.dataset.customErrorMessage || \'\')" oninput="this.setCustomValidity(\'\')" /><span class="papertiger-radio-item__label">' . _\Util::escapeRenderValue($this->option->label) . '</span></label>';
    }
}
