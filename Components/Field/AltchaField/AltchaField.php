<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\AltchaField;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Field\AltchaField\AltchaFieldProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class AltchaField implements _\ComponentInterface
{
    private function __construct(
        private AltchaFieldProps $field,
    ) {
    }

    public static function create(
        AltchaFieldProps $field,
    ): self {
        return new self(
            field: $field,
        );
    }

    public function render(): string
    {
        return '<altcha-widget class="papertiger-field__control--altcha" auto="onfocus" display="standard"' . (($temp = $this->field->challengeUrl) === null ? '' : ' challenge="' . _\Util::escapeAttributeValue($temp) . '"') . '' . (($temp = $this->field->name) === null ? '' : ' name="' . _\Util::escapeAttributeValue($temp) . '"') . '></altcha-widget>';
    }
}
