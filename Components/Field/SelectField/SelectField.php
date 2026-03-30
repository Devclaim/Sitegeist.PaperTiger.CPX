<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\SelectField;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Field\SelectField\SelectFieldProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class SelectField implements _\ComponentInterface
{
    private function __construct(
        private SelectFieldProps $field,
        private ?_\ComponentInterface $content,
    ) {
    }

    public static function create(
        SelectFieldProps $field,
        _\ComponentInterface|string|null $content,
    ): self {
        return new self(
            field: $field,
            content: is_string($content) ? _\StringComponent::fromString($content) : $content,
        );
    }

    public function render(): string
    {
        return '<select' . (($temp = $this->field->fieldContainer->inputId) === null ? '' : ' id="' . _\Util::escapeAttributeValue($temp) . '"') . ' name="' . _\Util::escapeAttributeValue($this->field->name) . '" class="papertiger-field__control papertiger-field__control--select"' . (($temp = $this->field->isMultiple) === null ? '' : ($temp ? ' multiple' : '')) . '' . (($temp = $this->field->isRequired) === null ? '' : ($temp ? ' required' : '')) . '>' . (($temp = $this->content) === null ? '' : $temp->render()) . '</select>';
    }
}
