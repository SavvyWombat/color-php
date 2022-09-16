<?php

declare(strict_types=1);

namespace SavvyWombat\Color;

use Exception as BaseException;

class Exception extends BaseException
{
    public static function unregisteredColor(string $color): self
    {
        return new static("`{$color}` is not supported.");
    }

    public static function invalidChannel(string $channel, $value, $message): self
    {
        return new static("`{$value}` is not a valid value for `{$channel}`. `{$channel}` `{$message}`");
    }

    public static function invalidColorSpec(string $colorSpec): self
    {
        return new static("Could not find a registered color specification to match `{$colorSpec}`.");
    }

    public static function invalidHexSpec(string $colorSpec): self
    {
        return new static("`{$colorSpec}` is not a valid hex color specification.");
    }

    public static function invalidHslSpec(string $colorSpec): self
    {
        return new static("`{$colorSpec}` is not a valid hsl or hsla color specification.");
    }

    public static function invalidRgbSpec(string $colorSpec): self
    {
        return new static("`{$colorSpec}` is not a valid rgb or rgba color specification.");
    }
}
