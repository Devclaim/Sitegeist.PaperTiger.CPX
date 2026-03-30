<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Form;

use PackageFactory\ComponentEngine as _;
use Sitegeist\PaperTiger\CPX\Components\Form\FormProps;

#[\Neos\Flow\Annotations\Proxy(false)]
final readonly class Form implements _\ComponentInterface
{
    private function __construct(
        private FormProps $form,
        private ?_\ComponentInterface $content,
    ) {
    }

    public static function create(
        FormProps $form,
        _\ComponentInterface|string|null $content,
    ): self {
        return new self(
            form: $form,
            content: is_string($content) ? _\StringComponent::fromString($content) : $content,
        );
    }

    public function render(): string
    {
        return '<form id="' . _\Util::escapeAttributeValue($this->form->id) . '" class="papertiger-form__element"' . (($temp = $this->form->action) === null ? '' : ' action="' . _\Util::escapeAttributeValue($temp) . '"') . '' . (($temp = $this->form->method) === null ? '' : ' method="' . _\Util::escapeAttributeValue($temp) . '"') . '>' . (($temp = $this->content) === null ? '' : $temp->render()) . '</form>';
    }
}
