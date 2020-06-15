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
        'Hex' => Hex::class,
        'Hsl' => Hsl::class,
        'Rgb' => Rgb::class,
    ];

    private static $acceptedColorSpecs = [
        '#([0-9a-f])([0-9a-f])([0-9a-f])' => Hex::class,
        '#([0-9a-f])([0-9a-f])([0-9a-f])([0-9a-f])' => Hex::class,
        '#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})' => Hex::class,
        '#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})' => Hex::class,

        'hsl\((\d{1,3}),(\d{1,3}),(\d{1,3})\)' => Hsl::class,
        'hsla\((\d{1,3}),(\d{1,3}),(\d{1,3}),([0-1](.\d{1,2})?)\)' => Hsl::class,

        'rgb\((\d{1,3}),(\d{1,3}),(\d{1,3})\)' => Rgb::class,
        'rgba\((\d{1,3}),(\d{1,3}),(\d{1,3}),([0-1](.\d{1,2})?)\)' => Rgb::class,
    ];

    private static $availableModifiers = [
        'red' => Rgb::class,
        'blue' => Rgb::class,
        'green' => Rgb::class,
        'alpha' => Rgb::class,

        'hue' => Hsl::class,
        'saturation' => Hsl::class,
        'lightness' => Hsl::class,
    ];

    final protected static function extractChannels(string $colorSpec, string $filter): ?array
    {
        $accepted = array_keys(array_filter(
            AbstractColor::$acceptedColorSpecs,
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

    final protected static function supportedColor(string $color): ?string
    {
        if (isset(AbstractColor::$supportedColors[$color])) {
            return AbstractColor::$supportedColors[$color];
        }

        return null;
    }

    final public function toRgb(): Rgb
    {
        return new Rgb($this->red, $this->green, $this->blue, $this->alpha);
    }

    private function to($color): ColorInterface
    {
        if (AbstractColor::supportedColor($color) === null) {
            throw InvalidColorException::unsupportedColor($color);
        }

        return call_user_func([AbstractColor::supportedColor($color), 'fromRgb'], $this->toRgb());
    }

    final public function adjustValue($originalValue, $newValue)
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

    public function __call($name, $arguments)
    {
        if (substr($name, 0, 2) === 'to') {
            return $this->to(substr($name, 2));
        }

        if (isset(self::$availableModifiers[$name])) {
            $converter = (self::$availableModifiers[$name])::fromRgb($this->toRgb());
            $converter = $converter->$name($arguments[0]);
            return static::fromRgb($converter->toRgb());
        }
    }
}
