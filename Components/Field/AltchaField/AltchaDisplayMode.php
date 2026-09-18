<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\AltchaField;

/**
 * Mirrors ALTCHA's `display` configuration option.
 *
 * @see https://altcha.org/docs/v2/website-integration/
 */
enum AltchaDisplayMode: string
{
    case STANDARD = 'standard';
    case BAR = 'bar';
    case FLOATING = 'floating';
    case OVERLAY = 'overlay';
    case INVISIBLE = 'invisible';
}
