<?php

declare(strict_types=1);

namespace SavvyWombat\Color;

abstract class AbstractColor
{
    protected $red;
    protected $green;
    protected $blue;
    protected $alpha;

    private static $supportedColors = [
        Hex::class,
        Hsl::class,
        Rgb::class,
    ];

    private static $acceptable = [
        '#([0-9a-f])([0-9a-f])([0-9a-f])' => Hex::class,
        '#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})' => Hex::class,
        '#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})' => Hex::class,

        'hsl\((\d{1,3}),(\d{1,3}),(\d{1,3})\)' => Hsl::class,
        'hsla\((\d{1,3}),(\d{1,3}),(\d{1,3}),([0-1](.\d{1,2})?)\)' => Hsl::class,

        'rgb\((\d{1,3}),(\d{1,3}),(\d{1,3})\)' => Rgb::class,
        'rgba\((\d{1,3}),(\d{1,3}),(\d{1,3}),([0-1](.\d{1,2})?)\)' => Rgb::class,
    ];

    final protected static function match(string $colorSpec, string $filter): ?array
    {
        $accepted = array_keys(array_filter(
            AbstractColor::$acceptable,
            function($value) use ($filter) {
                return $value === $filter;
            }
        ));

        $matches = [];
        while (empty($matches) && $accepted) {
            $pattern = array_pop($accepted);
            preg_match("/^ *{$pattern} *$/i", $colorSpec, $matches);
        }
        
        return $matches;
    }

    public function alpha($alpha): ColorInterface
    {
        $alpha = $this->adjustValue($this->alpha, $alpha);

        $rgb = new Rgb($this->red, $this->green, $this->blue, $alpha);
        return static::fromRgb($rgb);
    }

    public function toRgb(): Rgb
    {
        return new Rgb($this->red, $this->green, $this->blue, $this->alpha);
    }

    public function to($color): ColorInterface
    {
        if (!in_array($color, AbstractColor::$supportedColors)) {
            throw InvalidColorException::unsupportedColor($color);
        }

        return call_user_func([$color, 'fromRgb'], $this->toRgb());
    }

    public final function adjustValue($originalValue, $newValue)
    {
        if (is_string($newValue)) {
            if ($newValue{0} === '+') {
                $delta = substr($newValue, 1);
                if (substr($delta, -1) === '%') {
                    $delta = $originalValue * (substr($delta, 0, -1) / 100);
                }
                return $originalValue + $delta;
            }

            if ($newValue{0} === '-') {
                $delta = substr($newValue, 1);
                if (substr($delta, -1) === '%') {
                    $delta = $originalValue * (substr($delta, 0, -1) / 100);
                }
                return $originalValue - $delta;
            }
        }

        return (float) $newValue;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }
}
