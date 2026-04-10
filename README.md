# Sitegeist.PaperTiger.CPX

`Sitegeist.PaperTiger.CPX` is intentionally customizable at a few different levels, depending on how much of the form experience you want to own.

## 1. Customize the CSS

If no markup changes are needed, use CSS.

Example:

```css
@layer components {
    .papertiger-field__control {
        @apply border border-brand/20 bg-brand-grey/45 px-24 py-16;
    }
}
```

## 2. Customize the Form Wrapper

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

## 3. Replace Shared Field Components

If you want to change shared field markup, replace the shared components.

- `fieldContainer`
- `label`
- `input`
- `textarea`
- `select`
- `upload`
- `checkbox`
- `radio`
- `date`

These components must keep the same PaperTiger props.

Example project settings:

```yaml
Sitegeist:
  PaperTiger:
    CPX:
      components:
        fieldContainer: 'Vendor\Shared\Components\Block\FormBuilder\Fields\FieldContainer\FieldContainer'
        label: 'Vendor\Shared\Components\Block\FormBuilder\Fields\Label\Label'
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

## 4. Create Your Own Node Types via Mixins

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
