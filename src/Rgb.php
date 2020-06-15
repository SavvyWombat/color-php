<?php

declare(strict_types=1);

namespace SavvyWombat\Color;

class Rgb extends AbstractColor implements ColorInterface
{
    public function __construct(float $red, float $green, float $blue, float $alpha = 1.0)
    {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->alpha = $alpha;
    }

    public static function fromString(string $colorSpec): ColorInterface
    {
        $matches = parent::match($colorSpec, self::class);

        if (empty($matches)) {
            throw InvalidColorSpecException::invalidRgbSpec($colorSpec);
        }
        if (!isset($matches[4])) {
            $matches[4] = 1;
        }

        return new Rgb((int) $matches[1], (int) $matches[2], (int) $matches[3], (float) $matches[4]);
    }

    public static function fromRgb(Rgb $rgb): ColorInterface
    {
        return new Rgb(
            $rgb->red,
            $rgb->green,
            $rgb->blue,
            $rgb->alpha
        );
    }

    public function __toString(): string
    {
        if ($this->alpha === 1.0) {
            return "rgb({$this->red},{$this->green},{$this->blue})";
        }

        return "rgba({$this->red},{$this->green},{$this->blue},{$this->alpha})";
    }
}
