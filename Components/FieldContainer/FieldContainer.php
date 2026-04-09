<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\FieldContainer;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class FieldContainer implements _\ComponentInterface
{
    private function __construct(
        private FieldContainerProps $fieldContainer,
        private ?_\ComponentInterface $label,
        private ?_\ComponentInterface $content,
    ) {
    }

    public static function create(
        FieldContainerProps $fieldContainer,
        _\ComponentInterface|string|null $label,
        _\ComponentInterface|string|null $content,
    ): self {
        return new self(
            fieldContainer: $fieldContainer,
            label: is_string($label) ? _\StringComponent::fromString($label) : $label,
            content: is_string($content) ? _\StringComponent::fromString($content) : $content,
        );
    }

    public function render(): string
    {
        return '<div' . (($temp = $this->fieldContainer->id) === null ? '' : ' id="' . _\Util::escapeAttributeValue($temp) . '"') . ' class="papertiger-field" data-form-field>' . (($temp = $this->label) === null ? '' : $temp->render()) . '' . (($temp = $this->content) === null ? '' : $temp->render()) . '</div>';
    }
}
