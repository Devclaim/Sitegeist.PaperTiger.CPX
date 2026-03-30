<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\RadioGroupField;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Field\RadioGroupField\RadioGroupFieldProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class RadioGroupField implements _\ComponentInterface
{
    private function __construct(
        private ?_\ComponentInterface $content,
    ) {
    }

    public static function create(
        RadioGroupFieldProps $field,
        _\ComponentInterface|string|null $content,
    ): self {
        return new self(
            content: is_string($content) ? _\StringComponent::fromString($content) : $content,
        );
    }

    public function render(): string
    {
        return '<div class="papertiger-field__options papertiger-field__options--radios">' . (($temp = $this->content) === null ? '' : $temp->render()) . '</div>';
    }
}
