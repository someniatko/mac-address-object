<?php declare(strict_types = 1);

namespace Someniatko\MacAddressObject;

use MyCLabs\Enum\Enum;

/**
 * @method static self DASHES
 * @method static self COLONS
 * @method static self DOTS
 * @method static self NO_DELIMITERS
 */
final class MacFormat extends Enum
{
    public const DASHES = 1;
    public const COLONS = 2;
    public const DOTS = 3;
    public const NO_DELIMITERS = 4;
}
