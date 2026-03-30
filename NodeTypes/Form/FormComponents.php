<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Form;

use Sitegeist\PaperTiger\CPX\Components\Field\ButtonField\ButtonField;
use Sitegeist\PaperTiger\CPX\Components\Field\CheckboxGroupField\CheckboxGroupField;
use Sitegeist\PaperTiger\CPX\Components\Field\CheckboxItem\CheckboxItem;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainer;
use Sitegeist\PaperTiger\CPX\Components\Field\FriendlyCaptchaField\FriendlyCaptchaField;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenField;
use Sitegeist\PaperTiger\CPX\Components\Field\HoneypotField\HoneypotField;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputField;
use Sitegeist\PaperTiger\CPX\Components\Label\Label;
use Sitegeist\PaperTiger\CPX\Components\Field\RadioGroupField\RadioGroupField;
use Sitegeist\PaperTiger\CPX\Components\Field\RadioItem\RadioItem;
use Sitegeist\PaperTiger\CPX\Components\Field\SelectField\SelectField;
use Sitegeist\PaperTiger\CPX\Components\Field\TextareaField\TextareaField;
use Sitegeist\PaperTiger\CPX\Components\Field\UploadField\UploadField;
use Sitegeist\PaperTiger\CPX\Components\Fieldset\Fieldset;

final readonly class FormComponents
{
    private function __construct(
        private ?string $buttonFieldComponent,
        private ?string $checkboxGroupFieldComponent,
        private ?string $checkboxItemComponent,
        private ?string $fieldContainerComponent,
        private ?string $fieldsetComponent,
        private ?string $friendlyCaptchaFieldComponent,
        private ?string $hiddenFieldComponent,
        private ?string $honeypotFieldComponent,
        private ?string $inputFieldComponent,
        private ?string $labelComponent,
        private ?string $radioGroupFieldComponent,
        private ?string $radioItemComponent,
        private ?string $selectFieldComponent,
        private ?string $textareaFieldComponent,
        private ?string $uploadFieldComponent,
    ) {
    }

    public static function create(
        ?string $buttonField = null,
        ?string $checkboxGroupField = null,
        ?string $checkboxItem = null,
        ?string $fieldContainer = null,
        ?string $fieldset = null,
        ?string $friendlyCaptchaField = null,
        ?string $hiddenField = null,
        ?string $honeypotField = null,
        ?string $inputField = null,
        ?string $label = null,
        ?string $radioGroupField = null,
        ?string $radioItem = null,
        ?string $selectField = null,
        ?string $textareaField = null,
        ?string $uploadField = null,
    ): self {
        return new self(
            buttonFieldComponent: $buttonField,
            checkboxGroupFieldComponent: $checkboxGroupField,
            checkboxItemComponent: $checkboxItem,
            fieldContainerComponent: $fieldContainer,
            fieldsetComponent: $fieldset,
            friendlyCaptchaFieldComponent: $friendlyCaptchaField,
            hiddenFieldComponent: $hiddenField,
            honeypotFieldComponent: $honeypotField,
            inputFieldComponent: $inputField,
            labelComponent: $label,
            radioGroupFieldComponent: $radioGroupField,
            radioItemComponent: $radioItem,
            selectFieldComponent: $selectField,
            textareaFieldComponent: $textareaField,
            uploadFieldComponent: $uploadField,
        );
    }

    public function buttonFieldComponent(): string { return $this->buttonFieldComponent ?? ButtonField::class; }
    public function checkboxGroupFieldComponent(): string { return $this->checkboxGroupFieldComponent ?? CheckboxGroupField::class; }
    public function checkboxItemComponent(): string { return $this->checkboxItemComponent ?? CheckboxItem::class; }
    public function fieldContainerComponent(): string { return $this->fieldContainerComponent ?? FieldContainer::class; }
    public function fieldsetComponent(): string { return $this->fieldsetComponent ?? Fieldset::class; }
    public function friendlyCaptchaFieldComponent(): string { return $this->friendlyCaptchaFieldComponent ?? FriendlyCaptchaField::class; }
    public function hiddenFieldComponent(): string { return $this->hiddenFieldComponent ?? HiddenField::class; }
    public function honeypotFieldComponent(): string { return $this->honeypotFieldComponent ?? HoneypotField::class; }
    public function inputFieldComponent(): string { return $this->inputFieldComponent ?? InputField::class; }
    public function labelComponent(): string { return $this->labelComponent ?? Label::class; }
    public function radioGroupFieldComponent(): string { return $this->radioGroupFieldComponent ?? RadioGroupField::class; }
    public function radioItemComponent(): string { return $this->radioItemComponent ?? RadioItem::class; }
    public function selectFieldComponent(): string { return $this->selectFieldComponent ?? SelectField::class; }
    public function textareaFieldComponent(): string { return $this->textareaFieldComponent ?? TextareaField::class; }
    public function uploadFieldComponent(): string { return $this->uploadFieldComponent ?? UploadField::class; }
}
