<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\UploadField;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Field\UploadField\UploadFieldProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class UploadField implements _\ComponentInterface
{
    private function __construct(
        private UploadFieldProps $field,
    ) {
    }

    public static function create(
        UploadFieldProps $field,
    ): self {
        return new self(
            field: $field,
        );
    }

    public function render(): string
    {
        return '<input type="file"' . (($temp = $this->field->fieldContainer->inputId) === null ? '' : ' id="' . _\Util::escapeAttributeValue($temp) . '"') . ' name="' . _\Util::escapeAttributeValue($this->field->name) . '" class="papertiger-field__control papertiger-field__control--upload"' . (($temp = $this->field->isRequired) === null ? '' : ($temp ? ' required' : '')) . '' . (($temp = $this->field->isMultiple) === null ? '' : ($temp ? ' multiple' : '')) . ' />';
    }
}
