<?php

declare(strict_types=1);

namespace SavvyWombat\Color;

interface ColorInterface
{
    public function red(): int;
    public function green(): int;
    public function blue(): int;
    public function alpha(): float;

    public function fromString(string $colorSpec): ColorInterface;
}
