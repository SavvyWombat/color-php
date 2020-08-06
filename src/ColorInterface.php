<?php

declare(strict_types=1);

namespace SavvyWombat\Color;

interface ColorInterface
{
    public static function fromString(string $colorSpec): ColorInterface;

    public function __toString(): string;

    public static function fromRgb(Rgb $rgb): ColorInterface;

    public function toRgb(): Rgb;
}
