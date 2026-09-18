<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Form;

enum FormMode : string
{
    case FORM_MODE_STANDARD = 'standard';
    case FORM_MODE_ASYNC = 'async';

    /**
     * Fully qualified label id for the inspector's select box editor.
     *
     * Deliberately explicit rather than relying on `internationalize: true`: that magic
     * resolves 'i18n' against the node type the property is declared on, so subclasses of
     * Sitegeist.PaperTiger.CPX:Form (e.g. Vendor.WheelInventor:Content.FormBuilder) would
     * look the label up in their own package instead of here.
     */
    public function getSelectBoxEditorLabel(): string
    {
        return 'Sitegeist.PaperTiger.CPX:NodeTypes.Form:properties.formMode.selectBoxEditor.values.'
            . $this->value;
    }

    public function asString(): string
    {
        return $this->value;
    }

    public function asText(): string
    {
        return $this->value;
    }

    public function asAttributeValue(): string
    {
        return $this->value;
    }
}
