<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Form;

enum FormMode : string
{
    case FORM_MODE_STANDARD = 'standard';
    case FORM_MODE_ASYNC = 'async';
}
