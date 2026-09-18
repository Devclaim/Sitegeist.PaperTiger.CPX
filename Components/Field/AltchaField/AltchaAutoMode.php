<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\AltchaField;

/**
 * Mirrors ALTCHA's `auto` configuration option.
 *
 * Hand written on purpose - unlike FormMode there is no AltchaAutoMode.cpx, so
 * `flow components:build` leaves this file alone.
 *
 * @see https://altcha.org/docs/v2/website-integration/
 */
enum AltchaAutoMode: string
{
    case OFF = 'off';
    case ON_FOCUS = 'onfocus';
    case ON_LOAD = 'onload';
    case ON_SUBMIT = 'onsubmit';
}
