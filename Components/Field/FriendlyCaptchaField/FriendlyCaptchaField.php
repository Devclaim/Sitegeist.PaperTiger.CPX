<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\FriendlyCaptchaField;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Field\FriendlyCaptchaField\FriendlyCaptchaFieldProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class FriendlyCaptchaField implements _\ComponentInterface
{
    private function __construct(
        private FriendlyCaptchaFieldProps $field,
    ) {
    }

    public static function create(
        FriendlyCaptchaFieldProps $field,
    ): self {
        return new self(
            field: $field,
        );
    }

    public function render(): string
    {
        return '<div class="papertiger-field__control papertiger-field__control--friendly-captcha friendly-captcha"' . (($temp = $this->field->name) === null ? '' : ' data-friendly-captcha-field="' . _\Util::escapeAttributeValue($temp) . '"') . ' />';
    }
}
