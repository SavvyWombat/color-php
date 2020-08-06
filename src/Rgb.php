<?php

declare(strict_types=1);

namespace SavvyWombat\Color;

class Rgb extends Color
{
    /**
     * While RGB channels are technically integer values in the range of 0..255, we accept floats here to prevent
     * loss of color information when converting between color types.
     *
     * RGB channels are rounded to integer values in the __toString() method.
     */
    public function __construct(float $red, float $green, float $blue, float $alpha = 1.0)
    {
        $this->red = self::validateRgbChannel('red', $red);
        $this->green = self::validateRgbChannel('green', $green);
        $this->blue = self::validateRgbChannel('blue', $blue);
        $this->alpha = self::validateAlphaChannel($alpha);
    }

    public static function validateRgbChannel($channel, $value)
    {
        if ($value < 0 || $value > 255) {
            throw Exception::invalidChannel($channel, $value, 'must be a valid rgb value (0-255)');
        }

        return $value;
    }

    public static function fromString(string $colorSpec): ColorInterface
    {
        $channels = Color::extractChannels($colorSpec, self::class);

        if (empty($channels)) {
            throw Exception::invalidRgbSpec($colorSpec);
        }
        if ( ! isset($channels[4])) {
            $channels[4] = 1;
        }

        return new Rgb((int) $channels[1], (int) $channels[2], (int) $channels[3], (float) $channels[4]);
    }

    public function red($red): self
    {
        $red = $this->adjustValue($this->red, $red, 255);

        if ($red > 255) {
            $red = 255;
        }

        if ($red < 0) {
            $red = 0;
        }

        return new self($red, $this->green, $this->blue, $this->alpha);
    }

    public function green($green): self
    {
        $green = $this->adjustValue($this->green, $green, 255);

        if ($green > 255) {
            $green = 255;
        }

        if ($green < 0) {
            $green = 0;
        }

        return new self($this->red, $green, $this->blue, $this->alpha);
    }

    public function blue($blue): self
    {
        $blue = $this->adjustValue($this->blue, $blue, 255);

        if ($blue > 255) {
            $blue = 255;
        }

        if ($blue < 0) {
            $blue = 0;
        }

        return new self($this->red, $this->green, $blue, $this->alpha);
    }

    public function alpha($alpha): self
    {
        $alpha = $this->adjustValue($this->alpha, $alpha, 1);

        if ($alpha > 1) {
            $alpha = 1;
        }

        if ($alpha < 0) {
            $alpha = 0;
        }

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
        $red = round($this->red);
        $green = round($this->green);
        $blue = round($this->blue);
        $alpha = round($this->alpha, 2);

        if (1.0 === $alpha) {
            return "rgb({$red},{$green},{$blue})";
        }

        return "rgba({$red},{$green},{$blue},{$alpha})";
    }
}
