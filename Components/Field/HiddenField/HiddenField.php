<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\HiddenField;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenFieldProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class HiddenField implements _\ComponentInterface
{
    private function __construct(
        private HiddenFieldProps $field,
    ) {
    }

    public static function create(
        HiddenFieldProps $field,
    ): self {
        return new self(
            field: $field,
        );
    }

    public function render(): string
    {
        return '<input type="hidden" name="' . _\Util::escapeAttributeValue($this->field->name) . '"' . (($temp = $this->field->value) === null ? '' : ' value="' . _\Util::escapeAttributeValue($temp) . '"') . ' class="papertiger-field__control papertiger-field__control--hidden" />';
    }
}
