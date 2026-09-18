<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Components\Field\AltchaField;

/**
 * Mirrors ALTCHA's `type` configuration option - the visual style of the interaction
 * element. Left unset the widget applies its own default.
 *
 * @see https://altcha.org/docs/v2/website-integration/
 */
enum AltchaInteractionType: string
{
    case NATIVE = 'native';
    case CHECKBOX = 'checkbox';
    case SWITCH = 'switch';
}
