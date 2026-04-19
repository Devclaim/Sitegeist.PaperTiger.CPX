# Sitegeist.PaperTiger.CPX

## Form Modes

PaperTiger supports two form modes:

- `standard`: the form is rendered uncached, so on submit the server re-renders the page with values + validation errors.
- `async`: the form is rendered cached; JavaScript submits to the JSON endpoint and updates the UI with the returned errors/result.

## Validation

PaperTiger uses Flow validators (for example `NotEmpty`, `StringLength`, `RegularExpression`) to validate submitted values.

### How Validation Works

On submit, PaperTiger validates each field via a schema:

1. The field `SchemaProvider` builds a `SchemaDefinition` (target type + validators)
2. The schema converts the raw submitted value to the target type (via Flow PropertyMapper), for example:
   - `<input type="date">` submits `YYYY-MM-DD` as a string, but the schema converts it to `\DateTimeImmutable`
3. Validation runs on the converted value (if conversion worked). If conversion fails, validators run on the raw value.
4. If a value is empty, PaperTiger only runs the required validator and skips all other validators.

This keeps server validation consistent with browser inputs and avoids errors from validators that expect a specific PHP type.

### Inline Validation Messages

For text fields you can optionally override the inline validation messages in the inspector under the `Validation` tab.

1. Enable `Use custom message(s)` in the corresponding group (Required, Length, Pattern)
2. Fill in the message fields

These overrides are applied on the schema level (by Flow error code) so they work the same for `standard` and `async`.

### Browser Validation (Popup)

Fields can optionally define a custom browser popup message (native HTML validation).
This is configured in the inspector under `Validation` -> `Browser validation (popup)` and is used via `setCustomValidity(...)`.

### How Errors Are Stored And Rendered

PaperTiger collects all validation errors as a list of `{ fieldName, message }`.

- Field specific errors are attached to the field name (for example `email`, `message`, `upload`)
- General (form-level) errors use the special field name `__general`
- A field can have multiple errors; PaperTiger renders all errors for that field

### Error Codes (For Custom Messages)

Custom inline messages are implemented by overriding validator errors by their error code.
That means:

- Built-in Flow validators work out of the box (PaperTiger maps the relevant Flow error codes internally)
- Custom validators should use stable, unique integer error codes in `addError(...)` if you want to override them later

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

### Full Flow (Mixin -> Schema -> Validator)

If you want a custom validation, the typical flow is:

1. Add inspector properties via a mixin (options + optional custom message)
2. Add the mixin to your field node type
3. Implement a schema provider that reads the properties and adds validators
4. Implement the validator and use stable error codes
5. Map your field node type to your schema provider via settings

### 1. Validation mixin (YAML)

Example mixin that adds a checkbox + textarea for a custom server-side error:

```yaml
'Vendor.Site:Mixin.Validation.MyRule':
  abstract: true
  ui:
    inspector:
      groups:
        form-validation-my-rule:
          label: 'Validation - My rule'
          icon: icon-check
          tab: form-validation
          position: 90
  properties:
    myRuleEnabled:
      type: boolean
      defaultValue: false
      ui:
        label: 'Enable rule'
        inspector:
          group: form-validation-my-rule
          position: 10
    useCustomMyRuleMessage:
      type: boolean
      defaultValue: false
      ui:
        label: 'Use custom message'
        inspector:
          group: form-validation-my-rule
          position: 20
    myRuleMessage:
      type: string
      defaultValue: null
      ui:
        label: 'Rule error'
        inspector:
          group: form-validation-my-rule
          position: 30
          editor: 'Neos.Neos/Inspector/Editors/TextAreaEditor'
          hidden: 'ClientEval:node.properties.useCustomMyRuleMessage ? false : true'
          editorOptions:
            rows: 3
```

### 2. Field node type uses the mixin

```yaml
'Vendor.Site:Field.MyCustomField':
  superTypes:
    'Sitegeist.PaperTiger.CPX:Mixin.Field.Text.SingleLine': true
    'Vendor.Site:Mixin.Validation.MyRule': true
```

### 3. Schema provider builds the schema

```php
<?php

declare(strict_types=1);

namespace Vendor\Site\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\Schema\AbstractFieldSchemaProvider;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaInterface;
use Vendor\Site\Domain\Validation\Validator\MyRuleValidator;

final class MyCustomFieldSchemaProvider extends AbstractFieldSchemaProvider
{
    public function build(NeosContext $context, Node $fieldNode): ?SchemaInterface
    {
        $schema = $this->createSchema('string');

        $enabled = $context->nodes->getBoolValue($fieldNode, 'myRuleEnabled') ?? false;
        if ($enabled) {
            $schema->validator(MyRuleValidator::class, [
                'useCustomMessage' => $context->nodes->getBoolValue($fieldNode, 'useCustomMyRuleMessage') ?? false,
                'message' => $context->nodes->getStringValue($fieldNode, 'myRuleMessage'),
            ]);
        }

        return $schema;
    }
}
```

### 4. Validator implements the check (and error codes)

```php
<?php

declare(strict_types=1);

namespace Vendor\Site\Domain\Validation\Validator;

use Neos\Flow\Validation\Validator\AbstractValidator;

final class MyRuleValidator extends AbstractValidator
{
    public const int ERROR_INVALID = 1744800002;

    protected $supportedOptions = [
        'useCustomMessage' => [false, 'Whether to use the custom message', 'boolean', false],
        'message' => [null, 'Custom message', 'string', false],
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

        if ($value === 'forbidden') {
            $useCustom = (bool)$this->options['useCustomMessage'];
            $message = $useCustom && is_string($this->options['message']) && $this->options['message'] !== ''
                ? (string)$this->options['message']
                : 'The submitted value is invalid.';

            $this->addError($message, self::ERROR_INVALID);
        }
    }
}
```

### How mapping works

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

This is the full “field” flow (NodeType -> renderer/CPX -> schema -> validator).

### 1. Create the NodeType (YAML)

You can base it on an existing PaperTiger field mixin:

```yaml
'Vendor.Site:Field.MyCustomField':
  superTypes:
    'Sitegeist.PaperTiger.CPX:Mixin.Field.Text.SingleLine': true
  ui:
    label: 'My custom field'
    icon: icon-magic
```

### 2. Create a renderer (PHP) and field component (CPX)

Renderer:

```php
<?php

declare(strict_types=1);

namespace Vendor\Site\NodeTypes\Field\MyCustomField;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;

final class MyCustomFieldRenderer implements ContentNodeRendererInterface
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        // Typically: read node properties, create PaperTiger props, return your CPX component.
        return \Vendor\Site\Components\Form\MyCustomField::create(/* ... */);
    }
}
```

CPX (example):

```cpx
export component MyCustomField {
  render <input type="text" />
}
```

### 3. Add validation (SchemaProvider + Validator)

Use a `SchemaProvider` to map node properties into Flow validators (or your own validators).
See “Full Flow (Mixin -> Schema -> Validator)” above for a complete example.
