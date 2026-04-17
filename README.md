# Sitegeist.PaperTiger.CPX

## Form Modes

PaperTiger supports two form modes:

- `standard`: the form is rendered uncached, so on submit the server re-renders the page with values + validation errors.
- `async`: the form is rendered cached; JavaScript submits to the JSON endpoint and updates the UI with the returned errors/result.

## Customization

### 1. Customize the CSS

If no markup changes are needed, use CSS.

Example:

```css
@layer components {
    .papertiger-field__control {
        @apply border border-brand/20 bg-brand-grey/45 px-24 py-16;
    }
}
```

### 2. Customize the Form Wrapper

If you want your own outer wrapper, use `FormFactory` to build the form and pass it into your project component.

Example:

```php
return ContentContainerFactory::create(
    $context,
    FormBuilder::create(
        $context->neos->getEditable($context->node, 'headline', true),
        $this->formFactory->create($context),
    ),
);
```

### 3. Replace Shared Field Components

If you want to change shared field markup, replace the shared components.

- `fieldContainer`
- `label`
- `error`
- `input`
- `textarea`
- `select`
- `upload`
- `checkbox`
- `radio`
- `date`
- `message`

These components must keep the same PaperTiger props.

Example project settings:

```yaml
Sitegeist:
  PaperTiger:
    CPX:
      components:
        fieldContainer: 'Vendor\Shared\Components\Block\FormBuilder\Fields\FieldContainer\FieldContainer'
        label: 'Vendor\Shared\Components\Block\FormBuilder\Fields\Label\Label'
        error: 'Vendor\Shared\Components\Block\FormBuilder\Fields\Error\Error'
        message: 'Vendor\Shared\Components\Block\FormBuilder\Fields\Message\Message'
        input: 'Vendor\Shared\Components\Block\FormBuilder\Fields\Input\Input'
```

Example custom label component:

```cpx
from "Sitegeist.PaperTiger.CPX/Label/LabelProps.cpx" import { LabelProps }

export component Label {

    label: LabelProps

    render
        <label
            for={label.inputId}
            class="papertiger-field__label"
        >
            <span>{label.label}</span>
            {!label.isRequired ? <span>(optional)</span> : null}
        </label>
}
```

If the inner field markup is fine, import the PaperTiger component and wrap it.

Example custom input component:

```cpx
from "Sitegeist.PaperTiger.CPX/Field/InputField/InputField.cpx" import { InputField }
from "Sitegeist.PaperTiger.CPX/Field/InputField/InputFieldProps.cpx" import { InputFieldProps }
from "../InvalidIcon/InvalidIcon.cpx" import { InvalidIcon }

export component Input {

    field: InputFieldProps

    render
        <>
            <InputField field={field} />
            <InvalidIcon />
        </>
}
```

This is the easiest way to add small extras like an icon.

### 4. Create Your Own Node Types via Mixins

Use this only if the other options are not enough.

Example:

```yaml
'Vendor.Site:Content.Form.Field.Text.SingleLine':
  superTypes:
    'Sitegeist.PaperTiger.CPX:Mixin.Field.Text.SingleLine': true
```

This gives your project its own field types.

In CPX, you can also import PaperTiger props as structs.

Example:

```cpx
from "Sitegeist.PaperTiger.CPX/Field/InputField/InputFieldProps.cpx" import { InputFieldProps }

export component Input {

    field: InputFieldProps

    render
        <input
            type={field.type}
            id={field.fieldContainer.inputId}
            name={field.name}
        />
}
```

## Custom fields and validation

Validation is mapped per field node type.

For every field type, a schema provider builds the schema for that field.
A schema does not validate by itself — it only composes validators.

How mapping works

Add your field node type to the schema provider mapping, for example in Settings.yaml:

```yaml
Sitegeist:
  PaperTiger:
    CPX:
      validation:
        schemaProviders:
          'Vendor.Site:Field.MyCustomField': 'Vendor\Site\Domain\Validation\Schema\MyCustomFieldSchemaProvider'
```

The resolver will use that provider whenever a field node is of that type.

## How to add a custom field

### 1. Create a schema provider

Example:

```php
<?php

declare(strict_types=1);

namespace Vendor\Site\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaInterface;
use Sitegeist\PaperTiger\CPX\Domain\Validation\Schema\AbstractFieldSchemaProvider;
use Vendor\Site\Domain\Validation\Validator\MyCustomValidator;

final class MyCustomFieldSchemaProvider extends AbstractFieldSchemaProvider
{
    public function build(NeosContext $context, Node $fieldNode): ?SchemaInterface
    {
        $schema = $this->createSchema('string');

        if (($context->nodes->getBoolValue($fieldNode, 'isRequired') ?? false) === true) {
            $schema->isRequired();
        }

        $schema->validator(MyCustomValidator::class, [
            'someOption' => $context->nodes->getStringValue($fieldNode, 'someOption'),
        ]);

        return $schema;
    }
}
```

### 2. Create a custom validator

Example:

```php
<?php

declare(strict_types=1);

namespace Vendor\Site\Domain\Validation\Validator;

use Neos\Flow\Validation\Validator\AbstractValidator;

final class MyCustomValidator extends AbstractValidator
{
    protected $supportedOptions = [
        'someOption' => [null, 'Custom option', 'string', false],
    ];

    protected function isValid($value): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (!is_string($value)) {
            $this->addError('The value must be a string.', 1744800001);
            return;
        }

        if ($this->options['someOption'] !== null && $value !== $this->options['someOption']) {
            $this->addError('The submitted value is invalid.', 1744800002);
        }
    }
}
```
