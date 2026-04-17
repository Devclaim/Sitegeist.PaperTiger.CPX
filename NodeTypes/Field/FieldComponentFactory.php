<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field;

use Neos\Flow\Annotations as Flow;
use PackageFactory\ComponentEngine\ComponentInterface;
use Sitegeist\PaperTiger\CPX\Components\Field\CheckboxItem\CheckboxItem;
use Sitegeist\PaperTiger\CPX\Components\Field\CheckboxItem\CheckboxItemProps;
use Sitegeist\PaperTiger\CPX\Components\Error\Error;
use Sitegeist\PaperTiger\CPX\Components\Error\ErrorProps;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputField;
use Sitegeist\PaperTiger\CPX\Components\Field\InputField\InputFieldProps;
use Sitegeist\PaperTiger\CPX\Components\Field\RadioItem\RadioItem;
use Sitegeist\PaperTiger\CPX\Components\Field\RadioItem\RadioItemProps;
use Sitegeist\PaperTiger\CPX\Components\Field\SelectField\SelectField;
use Sitegeist\PaperTiger\CPX\Components\Field\SelectField\SelectFieldProps;
use Sitegeist\PaperTiger\CPX\Components\Field\TextareaField\TextareaField;
use Sitegeist\PaperTiger\CPX\Components\Field\TextareaField\TextareaFieldProps;
use Sitegeist\PaperTiger\CPX\Components\Field\UploadField\UploadField;
use Sitegeist\PaperTiger\CPX\Components\Field\UploadField\UploadFieldProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainer;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\Components\Label\Label;
use Sitegeist\PaperTiger\CPX\Components\Label\LabelProps;
use Sitegeist\PaperTiger\CPX\Components\Message\Message;
use Sitegeist\PaperTiger\CPX\Components\Message\MessageProps;

#[Flow\Scope('singleton')]
final class FieldComponentFactory
{
    /**
     * @param array{
     *   fieldContainer?: class-string<ComponentInterface>,
     *   label?: class-string<ComponentInterface>,
     *   error?: class-string<ComponentInterface>,
     *   input?: class-string<ComponentInterface>,
     *   textarea?: class-string<ComponentInterface>,
     *   select?: class-string<ComponentInterface>,
     *   date?: class-string<ComponentInterface>,
     *   upload?: class-string<ComponentInterface>,
     *   checkbox?: class-string<ComponentInterface>,
     *   radio?: class-string<ComponentInterface>,
     *   message?: class-string<ComponentInterface>
     * } $componentClasses
     */
    #[Flow\InjectConfiguration(path: 'components')]
    protected array $componentClasses;

    public function createFieldContainer(
        FieldContainerProps $fieldContainer,
        ComponentInterface|string|null $label,
        ComponentInterface|string|null $content,
        ComponentInterface|string|null $error,
    ): ComponentInterface {
        $className = $this->componentClasses['fieldContainer'] ?? FieldContainer::class;

        return $this->createComponent($className, $fieldContainer, $label, $content, $error);
    }

    public function createLabel(LabelProps $label): ComponentInterface
    {
        $className = $this->componentClasses['label'] ?? Label::class;

        return $this->createComponent($className, $label);
    }

    public function createError(ErrorProps $error): ComponentInterface
    {
        $className = $this->componentClasses['error'] ?? Error::class;

        return $this->createComponent($className, $error);
    }

    public function createMessage(MessageProps $message, ComponentInterface|string|null $content = null): ComponentInterface
    {
        $className = $this->componentClasses['message'] ?? Message::class;

        return $this->createComponent($className, $message, $content);
    }

    public function createInput(InputFieldProps $field): ComponentInterface
    {
        $className = $this->componentClasses['input'] ?? InputField::class;

        return $this->createComponent($className, $field);
    }

    public function createTextarea(TextareaFieldProps $field): ComponentInterface
    {
        $className = $this->componentClasses['textarea'] ?? TextareaField::class;

        return $this->createComponent($className, $field);
    }

    public function createSelect(SelectFieldProps $field, ComponentInterface|string|null $content): ComponentInterface
    {
        $className = $this->componentClasses['select'] ?? SelectField::class;

        return $this->createComponent($className, $field, $content);
    }

    public function createDate(InputFieldProps $field): ComponentInterface
    {
        $className = $this->componentClasses['date']
            ?? $this->componentClasses['input']
            ?? InputField::class;

        return $this->createComponent($className, $field);
    }

    public function createUpload(UploadFieldProps $field): ComponentInterface
    {
        $className = $this->componentClasses['upload'] ?? UploadField::class;

        return $this->createComponent($className, $field);
    }

    public function createCheckbox(CheckboxItemProps $option): ComponentInterface
    {
        $className = $this->componentClasses['checkbox'] ?? CheckboxItem::class;

        return $this->createComponent($className, $option);
    }

    public function createRadio(RadioItemProps $option): ComponentInterface
    {
        $className = $this->componentClasses['radio'] ?? RadioItem::class;

        return $this->createComponent($className, $option);
    }

    private function createComponent(string $className, mixed ...$arguments): ComponentInterface
    {
        if (!class_exists($className)) {
            throw new \RuntimeException(sprintf('Configured PaperTiger component class "%s" does not exist.', $className), 1744041001);
        }

        if (!is_a($className, ComponentInterface::class, true)) {
            throw new \RuntimeException(sprintf('Configured PaperTiger component class "%s" must implement %s.', $className, ComponentInterface::class), 1744041002);
        }

        if (!method_exists($className, 'create')) {
            throw new \RuntimeException(sprintf('Configured PaperTiger component class "%s" must provide a static create() method.', $className), 1744041003);
        }

        try {
            /** @var ComponentInterface $component */
            $component = $className::create(...$arguments);
        } catch (\TypeError $exception) {
            throw new \RuntimeException(sprintf('Configured PaperTiger component class "%s" has an incompatible create() signature.', $className), 1744041004, $exception);
        }

        return $component;
    }
}
