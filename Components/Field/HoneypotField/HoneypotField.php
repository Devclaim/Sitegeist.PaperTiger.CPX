<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\HoneypotField;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Field\HoneypotField\HoneypotFieldProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class HoneypotField implements _\ComponentInterface
{
    private function __construct(
        private HoneypotFieldProps $field,
        private ?_\ComponentInterface $script,
    ) {
    }

    public static function create(
        HoneypotFieldProps $field,
        _\ComponentInterface|string|null $script,
    ): self {
        return new self(
            field: $field,
            script: is_string($script) ? _\StringComponent::fromString($script) : $script,
        );
    }

    public function render(): string
    {
        return '<div class="papertiger-field__control papertiger-field__control--honeypot"' . (($temp = $this->field->style) === null ? '' : ' style="' . _\Util::escapeAttributeValue($temp) . '"') . '><input type="text" name="' . _\Util::escapeAttributeValue($this->field->firstInputName) . '" value="" autocomplete="off" tabindex="-1" class="papertiger-field__input papertiger-field__input--honeypot papertiger-field__input--honeypot-first" /><input type="text" name="' . _\Util::escapeAttributeValue($this->field->secondInputName) . '" value="' . _\Util::escapeAttributeValue($this->field->timestampWithHmac) . '" autocomplete="off" tabindex="-1" class="papertiger-field__input papertiger-field__input--honeypot papertiger-field__input--honeypot-second" /><input type="text" id="' . _\Util::escapeAttributeValue($this->field->scriptTargetId) . '" name="' . _\Util::escapeAttributeValue($this->field->thirdInputName) . '" value="" autocomplete="off" tabindex="-1" class="papertiger-field__input papertiger-field__input--honeypot papertiger-field__input--honeypot-third" />' . (($temp = $this->script) === null ? '' : $temp->render()) . '</div>';
    }
}
