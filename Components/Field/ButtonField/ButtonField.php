<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\ButtonField;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Field\ButtonField\ButtonFieldProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class ButtonField implements _\ComponentInterface
{
    private function __construct(
        private ButtonFieldProps $field,
    ) {
    }

    public static function create(
        ButtonFieldProps $field,
    ): self {
        return new self(
            field: $field,
        );
    }

    public function render(): string
    {
        return '<button type="submit" class="papertiger-field__button papertiger-field__button--submit">' . (($temp = $this->field->label) === null ? '' : _\Util::escapeRenderValue($temp)) . '</button>';
    }
}
