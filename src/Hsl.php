<?php

declare(strict_types=1);

namespace SavvyWombat\Color;

class Hsl extends Color
{
    protected $hue;
    protected $saturation;
    protected $lightness;

    public function __construct(float $hue, float $saturation, float $lightness, $alpha = 1.0)
    {
        $this->hue = $hue;
        $this->saturation = self::validateHslChannel('saturation)', $saturation);
        $this->lightness = self::validateHslChannel('lightness', $lightness);
        $this->alpha = self::validateAlphaChannel($alpha);

        // calculate RGB equivalent values for easy conversion
        [$red, $green, $blue] = static::hslToRgb($this->hue, $this->saturation, $this->lightness);
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
    }

    public static function validateHslChannel($channel, $value)
    {
        if ($value < 0 || $value > 100) {
            throw Exception::invalidChannel($channel, $value, 'must be a valid hsl value (0-100)');
        }

        return $value;
    }

    public static function fromString(string $colorSpec): ColorInterface
    {
        $channels = Color::extractChannels($colorSpec, self::class);

        if (empty($channels)) {
            throw Exception::invalidHslSpec($colorSpec);
        }
        if ( ! isset($channels[4])) {
            $channels[4] = 1;
        }

        return new Hsl((float) $channels[1], (float) $channels[2], (float) $channels[3], (float) $channels[4]);
    }

    public function __toString(): string
    {
        $hue = round($this->hue);
        if (0.0 === $hue) { // deals with -0 from rounding
            $hue = 0;
        }
        $saturation = round($this->saturation, 1);
        $lightness = round($this->lightness, 1);
        $alpha = round($this->alpha, 2);

        if (1.0 === $alpha) {
            return "hsl({$hue},{$saturation}%,{$lightness}%)";
        }

        return "hsla({$hue},{$saturation}%,{$lightness}%,{$alpha})";
    }

    public function hue($hue): self
    {
        $hue = $this->adjustValue($this->hue, $hue);

        return new self($hue, $this->saturation, $this->lightness, $this->alpha);
    }

    public function saturation($saturation): self
    {
        $saturation = $this->adjustValue($this->saturation, $saturation, 100);

        if ($saturation > 100) {
            $saturation = 100;
        }

        if ($saturation < 0) {
            $saturation = 0;
        }

        return new self($this->hue, $saturation, $this->lightness, $this->alpha);
    }

    public function lightness($lightness): self
    {
        $lightness = $this->adjustValue($this->lightness, $lightness, 100);

        if ($lightness > 100) {
            $lightness = 100;
        }

        if ($lightness < 0) {
            $lightness = 0;
        }

        return new self($this->hue, $this->saturation, $lightness, $this->alpha);
    }

    public function invert(): self
    {
        $newHue = $this->hue >= 180 ? $this->hue - 180 : $this->hue + 180;

        return new self($newHue, $this->saturation, $this->lightness, $this->alpha);
    }

    public static function fromRgb(Rgb $rgb): ColorInterface
    {
        [$hue, $saturation, $lightness] = static::rgbToHsl($rgb->red, $rgb->green, $rgb->blue);

        return new Hsl($hue, $saturation, $lightness, $rgb->alpha);
    }

    public static function rgbToHsl(float $red, float $green, float $blue): array
    {
        $r = $red / 255;
        $g = $green / 255;
        $b = $blue / 255;

        $cmax = max($r, $g, $b);
        $cmin = min($r, $g, $b);
        $delta = $cmax - $cmin;

        $hue = 0;
        if ($delta > 0.0) {
            if ($r === $cmax) {
                $hue = 60 * fmod(($g - $b) / $delta, 6);
            }

            if ($g === $cmax) {
                $hue = 60 * ((($b - $r) / $delta) + 2);
            }

            if ($b === $cmax) {
                $hue = 60 * ((($r - $g) / $delta) + 4);
            }
        }

        $lightness = ($cmax + $cmin) / 2;
        $saturation = 0;
        if ($delta) {
            $saturation = $delta / (1 - abs((2 * $lightness) - 1));
        }

        return [$hue, min($saturation, 1) * 100, min($lightness, 1) * 100];
    }

    /**
     * @see https://drafts.csswg.org/css-color-3/#hsl-color
     */
    public static function hslToRgb(float $hue, float $saturation, float $lightness): array
    {
        $h = ((360 + ($hue % 360)) % 360) / 360;
        $s = $saturation / 100;
        $l = $lightness / 100;

        if ($l <= 0.5) {
            $m2 = $l * ($s + 1);
        } else {
            $m2 = $l + $s - ($l * $s);
        }

        $m1 = ($l * 2) - $m2;

        $red = static::hueToRgb($m1, $m2, $h + (1 / 3));
        $green = static::hueToRgb($m1, $m2, $h);
        $blue = static::hueToRgb($m1, $m2, $h - (1 / 3));

        return [$red, $green, $blue];
    }

    /**
     * @see https://drafts.csswg.org/css-color-3/#hsl-color
     */
    protected static function hueToRgb(float $m1, float $m2, float $h): float
    {
        if ($h < 0) {
            $h = $h + 1;
        }
        if ($h > 1) {
            $h = $h - 1;
        }

        if ($h * 6 < 1) {
            return ($m1 + ($m2 - $m1) * $h * 6) * 255;
        }
        if ($h * 2 < 1) {
            return $m2 * 255;
        }
        if ($h * 3 < 2) {
            return ($m1 + ($m2 - $m1) * ((2 / 3) - $h) * 6) * 255;
        }

        return $m1 * 255;
    }
}
