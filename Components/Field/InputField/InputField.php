<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\InputField;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputFieldProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class InputField implements _\ComponentInterface
{
    private function __construct(
        private InputFieldProps $field,
    ) {
    }

    public static function create(
        InputFieldProps $field,
    ): self {
        return new self(
            field: $field,
        );
    }

    public function render(): string
    {
        return '<input type="' . _\Util::escapeAttributeValue($this->field->type) . '"' . (($temp = $this->field->fieldContainer->inputId) === null ? '' : ' id="' . _\Util::escapeAttributeValue($temp) . '"') . ' name="' . _\Util::escapeAttributeValue($this->field->name) . '" class="papertiger-field__control" data-papertiger-field-type="' . _\Util::escapeAttributeValue($this->field->type) . '" data-fieldtype="input"' . ($this->field->customErrorMessageEnabled ? (($temp = $this->field->customErrorMessage) === null ? '' : ' data-custom-error-message="' . _\Util::escapeAttributeValue($temp) . '"') : '') . ' oninvalid="this.setCustomValidity(this.dataset.customErrorMessage || \'\')" oninput="this.setCustomValidity(\'\')"' . (($temp = $this->field->placeholder) === null ? '' : ' placeholder="' . _\Util::escapeAttributeValue($temp) . '"') . '' . (($temp = $this->field->isRequired) === null ? '' : ($temp ? ' required' : '')) . '' . (($temp = $this->field->regularExpression) === null ? '' : ' pattern="' . _\Util::escapeAttributeValue($temp) . '"') . '' . (($temp = $this->field->minimumLength) === null ? '' : ' min="' . _\Util::escapeAttributeValue($temp) . '"') . '' . (($temp = $this->field->maximumLength) === null ? '' : ' max="' . _\Util::escapeAttributeValue($temp) . '"') . '' . (($temp = $this->field->step) === null ? '' : ' step="' . _\Util::escapeAttributeValue($temp) . '"') . ' />';
    }
}
