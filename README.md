# Sitegeist.PaperTiger.CPX

`Sitegeist.PaperTiger.CPX` is intentionally customizable at a few different levels, depending on how much of the form experience you want to own.

## 1. Customize the CSS

If the markup is already fine, the easiest path is to just style the existing classes in your project.

Example:

```css
@layer components {
    .papertiger-field__control {
        @apply border border-brand/20 bg-brand-grey/45 px-24 py-16;
    }

    .papertiger-field.showInvalid .papertiger-field__control:invalid {
        @apply border-error;
    }
}
```

## 2. Customize the Form Wrapper

If you want to wrap the whole form differently, the place to do that is the form factory.

This is useful when your project already has a block component that should own the outer layout, for example a headline, grid, spacing, or wrapper around the actual PaperTiger form.

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

In that setup, PaperTiger still creates the full form via `FormFactory`, but the project wraps it in its own `FormBuilder` block component to add the headline, grid, and outer layout.


## 3. Create Your Own Node Types via Mixins

If you want your own project field node types, reuse the PaperTiger mixins and disable the originals via constraints.

Example:

```yaml
'Vendor.Site:Content.Form.Field.Text.SingleLine':
  superTypes:
    'Sitegeist.PaperTiger.CPX:Mixin.Field.Text.SingleLine': true
```

This is useful when your project wants its own editor-visible field types, labels, icons, or rendering behavior.

If your project components are written in CPX, you can also import PaperTiger props as structs directly and keep the same type contract.

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

## 4. Replace Shared Field Components

For many projects, the lightest and cleanest option is to keep the PaperTiger node types and just replace the shared components that many fields go through:

- `fieldContainer`
- `label`
- `input`
- `textarea`
- `select`
- `upload`
- `checkbox`
- `radio`
- `date`

These custom components must keep the exact PaperTiger prop signature.

Example project settings:

```yaml
Sitegeist:
  PaperTiger:
    CPX:
      components:
        fieldContainer: 'Vendor\Shared\Components\Block\FormBuilder\Fields\FieldContainer'
        label: 'Vendor\Shared\Components\Block\FormBuilder\Fields\Label'
        input: 'Vendor\Shared\Components\Block\FormBuilder\Fields\Input'
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

This approach keeps the package node types and renderer logic, while letting your project own the visual building blocks.
