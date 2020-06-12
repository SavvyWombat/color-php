<?php

declare(strict_types=1);

namespace SavvyWombat\Color;

interface ColorInterface
{
    public function red(): int;
    public function green(): int;
    public function blue(): int;
    public function alpha(): float;

    public static function fromString(string $colorSpec): ColorInterface;
    public function __toString(): string;

    public function toRgb(): Rgb;
    public static function fromRgb(Rgb $rgb): ColorInterface;
}
