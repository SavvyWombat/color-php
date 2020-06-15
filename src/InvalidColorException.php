<?php

namespace SavvyWombat\Color;

use Exception;

class InvalidColorException extends Exception
{
    public static function unsupportedColor(string $color): self
    {
        return new static("`{$color}` is not supported.");
    }

    public static function invalidChannel(string $channel, $value, $message): self
    {
        return new static("`{$value}` is not a valid value for `{$channel}`. `{$channel}` `{$message}`");
    }

    public static function invalidHexSpec(string $colorSpec): self
    {
        return new static("`$colorSpec` is not a valid hex color specification.");
    }

    public static function invalidHslSpec(string $colorSpec): self
    {
        return new static("`$colorSpec` is not a valid hsl or hsla color specification.");
    }

    public static function invalidRgbSpec(string $colorSpec): self
    {
        return new static("`$colorSpec` is not a valid rgb or rgba color specification.");
    }
}
