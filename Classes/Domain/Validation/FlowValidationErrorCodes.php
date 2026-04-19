<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation;

/**
 * Central place for Flow validator error codes we want to map/override.
 *
 * These codes come from Neos Flow's built-in validators.
 */
final class FlowValidationErrorCodes
{
    /**
     * NotEmptyValidator uses multiple codes depending on the "empty" value type.
     * @var list<int>
     */
    public const REQUIRED_CODES = [
        1221560910, // null
        1221560718, // empty string
        1354192543, // empty array
        1354192552, // empty Countable
    ];

    public const STRING_LENGTH_BETWEEN = 1238108067;
    public const STRING_LENGTH_MIN = 1238108068;
    public const STRING_LENGTH_MAX = 1238108069;

    public const REGEX_MISMATCH = 1221565130;

    public const DATE_TIME_RANGE_BETWEEN = 1325615630;
    public const DATE_TIME_RANGE_AFTER = 1324315107;
    public const DATE_TIME_RANGE_BEFORE = 1324315115;

    public const UPLOAD_EXTENSION_NOT_ALLOWED = 1744721003;
    public const UPLOAD_SIZE_EXCEEDED = 1744721004;
}
