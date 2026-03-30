<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\TextareaField;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Field\TextareaField\TextareaFieldProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class TextareaField implements _\ComponentInterface
{
    private function __construct(
        private TextareaFieldProps $field,
    ) {
    }

    public static function create(
        TextareaFieldProps $field,
    ): self {
        return new self(
            field: $field,
        );
    }

    public function render(): string
    {
        return '<textarea' . (($temp = $this->field->fieldContainer->inputId) === null ? '' : ' id="' . _\Util::escapeAttributeValue($temp) . '"') . ' name="' . _\Util::escapeAttributeValue($this->field->name) . '" class="papertiger-field__control papertiger-field__control--textarea"' . (($temp = $this->field->placeholder) === null ? '' : ' placeholder="' . _\Util::escapeAttributeValue($temp) . '"') . '' . (($temp = $this->field->isRequired) === null ? '' : ($temp ? ' required' : '')) . '' . (($temp = $this->field->lineNumber) === null ? '' : ' rows="' . $temp . '"') . '' . (($temp = $this->field->minimumLength) === null ? '' : ' minlength="' . $temp . '"') . '' . (($temp = $this->field->maximumLength) === null ? '' : ' maxlength="' . $temp . '"') . '>' . (($this->field->value !== null) ? (($temp = $this->field->value) === null ? '' : _\Util::escapeRenderValue($temp)) : '') . '</textarea>';
    }
}
