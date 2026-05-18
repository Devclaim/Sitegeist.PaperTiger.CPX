<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Form;

enum ActionType : string
{
    case MESSAGE = 'message';
    case REDIRECT = 'redirect';
}