<?php

declare(strict_types=1);

namespace SavvyWombat\Color;

class Hex extends AbstractColor implements ColorInterface
{
    protected $hexRed;
    protected $hexGreen;
    protected $hexBlue;
    protected $hexAlpha;

    public function __construct(string $red, string $green, string $blue, string $alpha = 'ff')
    {
        if (strlen($red) === 1) {
            $red .= $red;
        }
        if (strlen($green) === 1) {
            $green .= $green;
        }
        if (strlen($blue) === 1) {
            $blue .= $blue;
        }
        if (strlen($alpha) === 1) {
            $alpha .= $alpha;
        }

        $this->hexRed = strtoLower(self::validateChannel('red', $red));
        $this->hexGreen = strtolower(self::validateChannel('green', $green));
        $this->hexBlue = strtolower(self::validateChannel('blue', $blue));
        $this->hexAlpha = strtolower( self::validateChannel('alpha', $alpha));

        $this->red = hexdec($red);
        $this->green = hexdec($green);
        $this->blue = hexdec($blue);
        $this->alpha = hexdec($alpha) / 255;
    }

    public static function validateChannel($channel, $value)
    {
        if (!preg_match("/^[0-9a-f]{2}$/i", $value)) {
            throw Exception::invalidChannel($channel, $value, 'must be a valid hex value (0-f or 00-ff)');
        }

        return $value;
    }

    public static function fromString(string $colorSpec): ColorInterface
    {
        $channels = parent::extractChannels($colorSpec, self::class);

        if (empty($channels)) {
            throw Exception::invalidHexSpec($colorSpec);
        }

        if (!isset($channels[4])) {
            $channels[4] = 'ff';
        }

        return new Hex($channels[1], $channels[2], $channels[3], $channels[4]);
    }

    public static function fromRgb(Rgb $rgb): ColorInterface
    {
        return new Hex(
            str_pad(dechex($rgb->red), 2, '0', STR_PAD_LEFT),
            str_pad(dechex($rgb->green), 2, '0', STR_PAD_LEFT),
            str_pad(dechex($rgb->blue), 2, '0', STR_PAD_LEFT),
            str_pad(dechex(round($rgb->alpha * 255)), 2, '0', STR_PAD_LEFT)
        );
    }

    public function __toString(): string
    {
        if ($this->hexAlpha === 'ff') {
            return "#{$this->hexRed}{$this->hexGreen}{$this->hexBlue}";
        }

        return "#{$this->hexRed}{$this->hexGreen}{$this->hexBlue}{$this->hexAlpha}";
    }
}