<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Fieldset;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Fieldset\FieldsetProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class Fieldset implements _\ComponentInterface
{
    private function __construct(
        private FieldsetProps $fieldset,
        private ?_\ComponentInterface $content,
    ) {
    }

    public static function create(
        FieldsetProps $fieldset,
        _\ComponentInterface|string|null $content,
    ): self {
        return new self(
            fieldset: $fieldset,
            content: is_string($content) ? _\StringComponent::fromString($content) : $content,
        );
    }

    public function render(): string
    {
        return '<fieldset' . (($temp = $this->fieldset->id) === null ? '' : ' id="' . _\Util::escapeAttributeValue($temp) . '"') . ' class="papertiger-fieldset"><legend class="papertiger-fieldset__legend">' . (($temp = $this->fieldset->label) === null ? '' : _\Util::escapeRenderValue($temp)) . '</legend>' . (($temp = $this->content) === null ? '' : $temp->render()) . '</fieldset>';
    }
}
