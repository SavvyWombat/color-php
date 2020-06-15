<?php

declare(strict_types=1);

namespace SavvyWombat\Color;

class Hsl extends AbstractColor implements ColorInterface
{
    protected $hue;
    protected $saturation;
    protected $lightness;

    public function __construct(float $hue, float $saturation, float $lightness, $alpha = 1.0)
    {
        $this->hue = $hue;
        $this->saturation = $saturation;
        $this->lightness = $lightness;
        $this->alpha = $alpha;

        [$red, $green, $blue] = static::hslToRgb($this->hue, $this->saturation, $this->lightness);
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
    }

    public static function fromString(string $colorSpec): ColorInterface
    {
        $matches = parent::match($colorSpec, self::class);

        if (empty($matches)) {
            throw InvalidColorSpecException::invalidHslSpec($colorSpec);
        }
        if (!isset($matches[4])) {
            $matches[4] = 1;
        }

        return new Hsl((float) $matches[1], (float) $matches[2], (float) $matches[3], (float) $matches[4]);
    }

    public function __toString(): string
    {
        $hue = round($this->hue);
        if ($hue === 0.0) { // deals with -0 from rounding
            $hue = 0;
        }
        $saturation = round($this->saturation);
        $lightness = round($this->lightness);

        if ($this->alpha === 1.0) {
            return "hsl({$hue},{$saturation},{$lightness})";
        }

        return "hsla({$hue},{$saturation},{$lightness},{$this->alpha})";
    }

    public function hue($hue): self
    {
        $hue = $this->adjustValue($this->hue, $hue);

        return new self($hue, $this->saturation, $this->lightness);
    }

    public function saturation($saturation): self
    {
        $saturation = $this->adjustValue($this->saturation, $saturation);

        return new self($this->hue, $saturation, $this->lightness);
    }

    public function lightness($lightness): self
    {
        $lightness = $this->adjustValue($this->lightness, $lightness);

        return new self($this->hue, $this->saturation, $lightness);
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
        $d = $cmax - $cmin;

        $hue = 0;
        if ($d !== 0) {
            if ($r === $cmax) {
                $hue = 60 * fmod(($g - $b) / $d, 6);
            }

            if ($g === $cmax) {
                $hue = 60 * ((($b - $r) / $d) + 2);
            }

            if ($b === $cmax) {
                $hue = 60 * ((($r - $g) / $d) + 4);
            }
        }

        $lightness = ($cmax + $cmin) / 2;
        $saturation = $d / (1 - abs((2 * $lightness) - 1));

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

        $red = static::hueToRgb($m1, $m2, $h + (1/3));
        $green = static::hueToRgb($m1, $m2, $h);
        $blue = static::hueToRgb($m1, $m2, $h - (1/3));

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
            return ($m1 + ($m2 - $m1) * ((2/3) - $h) * 6) * 255;
        }
        return $m1 * 255;
    }
}