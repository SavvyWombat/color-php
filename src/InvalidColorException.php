<?php

namespace SavvyWombat\Color;

use Exception;

class InvalidColorException extends Exception
{
    public static function unsupportedColor(string $color): self
    {
        return new static("`{$color}` is not supported.");
    }
}
