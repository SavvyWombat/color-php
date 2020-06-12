<?php

declare(strict_types=1);

namespace SavvyWombat\Color;

class Hex extends AbstractColor implements ColorInterface
{
    public function __construct(string $red, string $green, string $blue, string $alpha = 'ff') {
        if (strlen($red) === 1) {
            $red .= $red;
        }
        if (strlen($green) === 1) {
            $green .= $green;
        }
        if (strlen($blue) === 1) {
            $blue .= $blue;
        }

        $this->red = hexdec($red);
        $this->green = hexdec($green);
        $this->blue = hexdec($blue);
        $this->alpha = hexdec($alpha) / 255;
    }

    public function fromString(string $colorSpec): ColorInterface
    {
        $matches = parent::match($colorSpec, self::class);

        if (empty($matches)) {
            throw InvalidColorSpecException::invalidHexSpec($colorSpec);
        }

        if (!isset($matches[4])) {
            $matches[4] = 'ff';
        }

        return new Hex($matches[1], $matches[2], $matches[3], $matches[4]);
    }

    public function __toString(): string
    {
        $red = dechex($this->red);
        $green = dechex($this->green);
        $blue = dechex($this->blue);

        if ($this->alpha === 1) {
            return "#{$red}{$green}{$blue}";
        }

        $alpha = dechex($this->alpha * 255);
        return "#{$red}{$green}{$blue}{$alpha}";
    }
}