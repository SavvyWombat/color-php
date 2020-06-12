<?php

namespace SavvyWombat\Color;

use Exception;

class InvalidColorSpecException extends Exception
{
    public static function invalidHexSpec(string $colorSpec): self
    {
        return new static("`$colorSpec` is not a valid hex color specification.");
    }

    public static function invalidRgbSpec(string $colorSpec): self
    {
        return new static("`$colorSpec` is not a valid rgb or rgba color specification.");
    }
}