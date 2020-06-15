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
        $channels = parent::extractChannels($colorSpec, self::class);

        if (empty($channels)) {
            throw InvalidColorException::invalidRgbSpec($colorSpec);
        }
        if (!isset($channels[4])) {
            $channels[4] = 1;
        }

        return new Rgb((int) $channels[1], (int) $channels[2], (int) $channels[3], (float) $channels[4]);
    }

    public function red($red): self
    {
        $red = $this->adjustValue($this->red, $red);

        return new self($red, $this->green, $this->blue, $this->alpha);
    }

    public function green($green): self
    {
        $green = $this->adjustValue($this->green, $green);

        return new self($this->red, $green, $this->blue, $this->alpha);
    }

    public function blue($blue): self
    {
        $blue = $this->adjustValue($this->blue, $blue);

        return new self($this->red, $this->green, $blue, $this->alpha);
    }

    public function alpha($alpha): self
    {
        $alpha = $this->adjustValue($this->alpha, $alpha);

        return new self($this->red, $this->green, $this->blue, $alpha);
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
