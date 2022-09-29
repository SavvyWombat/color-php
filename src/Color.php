<?php

declare(strict_types=1);

namespace SavvyWombat\Color;

abstract class Color implements ColorInterface
{
    protected $red;
    protected $green;
    protected $blue;
    protected $alpha;

    /**
     * List of registered colors.
     * This allows conversion using methods such as `toHex`.
     *
     * More colors can be added or overridden via Color::registerColor()
     */
    private static $registeredColorTypes = [
        'Hex' => Hex::class,
        'Hsl' => Hsl::class,
        'Rgb' => Rgb::class,
    ];

    /**
     * List of registered color specifications.
     * Regex patterns to match against strings provided to Color::fromString().
     */
    private static $registeredColorSpecs = [
        '#([0-9a-f])([0-9a-f])([0-9a-f])' => Hex::class,
        '#([0-9a-f])([0-9a-f])([0-9a-f])([0-9a-f])' => Hex::class,
        '#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})' => Hex::class,
        '#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})' => Hex::class,

        'hsl\((-?\d+|\d*\.\d+),\s*(\d+|\d*\.\d+)%,\s*(\d+|\d*\.\d+)%\)' => Hsl::class,
        'hsl\((-?\d+|\d*\.\d+),\s*(\d+|\d*\.\d+)%,\s*(\d+|\d*\.\d+)%,\s*([0-1]|[0-1]?\.\d+)\)' => Hsl::class,
        'hsla\((-?\d+|\d*\.\d+),\s*(\d+|\d*\.\d+)%,\s*(\d+|\d*\.\d+)%,\s*([0-1]|[0-1]?\.\d+)\)' => Hsl::class,

        'rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)' => Rgb::class,
        'rgba\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3}),\s*([0-1](\.\d{1,2})?)\)' => Rgb::class,
    ];

    /**
     * List of registered modifiers.
     * Modifiers can be called on any object which extends Color.
     */
    private static $registeredModifiers = [
        'red' => Rgb::class,
        'blue' => Rgb::class,
        'green' => Rgb::class,
        'alpha' => Rgb::class,

        'hue' => Hsl::class,
        'saturation' => Hsl::class,
        'lightness' => Hsl::class,

        'invert' => Hsl::class,
    ];

    /**
     * Add or override a color model.
     * Maps the name to a class which extends Color and implements ColorInterface.
     *
     * This allows conversion using methods such as `toHex`.
     */
    final public static function registerColor(string $name, string $className): void
    {
        if ( ! class_exists($className)) {
            throw new Exception("Could not register `{$name}`. `{$className}` does not exist.");
        }

        if ( ! \in_array(ColorInterface::class, class_implements($className), true)) {
            throw new Exception("Could not register `{$name}`. `{$className}` does not implement `{ColorInterface::class}`.");
        }

        if ( ! \in_array(Color::class, class_parents($className), true)) {
            throw new Exception("Could not register `{$name}`. `{$className}` does not extend `{Color::class}`.");
        }

        static::$registeredColorTypes[$name] = $className;
    }

    final public static function registeredColors()
    {
        return static::$registeredColorTypes;
    }

    /**
     * Register a color specification pattern for matching the argument to Color::fromString() against a registered color.
     *
     * When searching, the regex will be completed as "/^ *{$pattern} *$/i" and any matches returned as an array so that fromString() can construct the relevant class.
     */
    final public static function registerColorSpec(string $pattern, string $className): void
    {
        if ( ! \in_array($className, static::$registeredColorTypes, true)) {
            throw new Exception("Could not register `{$pattern}` as a color spec. You must register the color `{$className}` first.");
        }

        static::$registeredColorSpecs[$pattern] = $className;
    }

    final public static function registeredColorSpecs()
    {
        return static::$registeredColorSpecs;
    }

    /**
     * Register a color modifier.
     * Modifiers map to methods on the specified class.
     */
    final public static function registerModifier(string $name, string $className): void
    {
        if ( ! \in_array($className, static::$registeredColorTypes, true)) {
            throw new Exception("Could not register `{$name}` as a modifier. You must register the color `{$className}` first.");
        }

        if ( ! method_exists($className, $name)) {
            throw new Exception("Could not register `{$name}` as a modifier. `{$className}` does not have this method.");
        }

        static::$registeredModifiers[$name] = $className;
    }

    final public static function registeredModifiers()
    {
        return static::$registeredModifiers;
    }

    /**
     * Extract channel data from the color specification $colorSpec.
     * The color specification will be tested against all registered patterns for the class name passed in $filter.
     *
     * A successful match will return the extracted data as an array.
     */
    final protected static function extractChannels(string $colorSpec, string $filter): ?array
    {
        $accepted = array_keys(array_filter(
            Color::$registeredColorSpecs,
            function ($value) use ($filter) {
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

    /**
     * Ensure the alpha channel is in the range 0..1.
     */
    public static function validateAlphaChannel($value)
    {
        if ($value < 0 || $value > 1) {
            throw Exception::invalidChannel('alpha', $value, 'must be a valid alpha value (0-1)');
        }

        return $value;
    }

    /**
     * Instantiate a color model from a color specification string.
     * Searches for a matching pattern in the list of registered color specifications and returns an instance of a matching class if one is found.
     */
    public static function fromString(string $colorSpec): ColorInterface
    {
        if (isset(Hex::namedColors()[$colorSpec])) {
            $colorSpec = Hex::namedColors()[$colorSpec];
        }

        $patterns = array_keys(self::$registeredColorSpecs);

        $found = false;
        foreach ($patterns as $pattern) {
            if (preg_match("/^ *{$pattern} *$/i", $colorSpec)) {
                $found = $pattern;
            }
        }

        if ( ! $found) {
            throw Exception::invalidColorSpec($colorSpec);
        }

        return (self::$registeredColorSpecs[$found])::fromString($colorSpec);
    }

    /**
     * Convert any subclass of Color to Rgb.
     * Expects the subclass to set RGB equivalents - otherwise the subclass will have to reimplement this method.
     */
    public function toRgb(): Rgb
    {
        return new Rgb($this->red, $this->green, $this->blue, $this->alpha);
    }

    /**
     * Handle conversion to the specified color.
     */
    private function to($color): ColorInterface
    {
        if ( ! isset(Color::registeredColors()[$color])) {
            throw Exception::unregisteredColor($color);
        }

        return \call_user_func([Color::registeredColors()[$color], 'fromRgb'], $this->toRgb());
    }

    /**
     * Takes the argument from a modifier and adjusts the provided value accordingly.
     * Accepts the following formats for $newValue.
     *
     * 50 will set the $originalValue to 50
     * +50 will add 50 to the $originalValue
     * -50 will remove 50 from the $originalValue
     * +50% will increase the $originalValue by 50%
     * -50% will decrease the $originalValue by 50%
     * +1/2 will return a value halfway between $originalValue and $max
     * -1/2 will return a value halfway between $originalValue and $min
     *
     * The calling modifier is responsible for ensuring the returned value is suitable for the modified property.
     */
    final public function adjustValue($originalValue, $newValue, $max = 0, $min = 0)
    {
        if (\is_string($newValue)) {
            if ('+' === $newValue[0]) {
                $delta = mb_substr($newValue, 1);
                if ('%' === mb_substr($delta, -1)) {
                    $delta = $originalValue * (mb_substr($delta, 0, -1) / 100);
                }
                if (mb_strpos($newValue, '/')) {
                    $matches = [];
                    if (preg_match('/^\+([0-9]+)\/([0-9]+)$/', $newValue, $matches)) {
                        $delta = ($max - $originalValue) * $matches[1] / $matches[2];
                    }
                }

                return $originalValue + $delta;
            }

            if ('-' === $newValue[0]) {
                $delta = mb_substr($newValue, 1);
                if ('%' === mb_substr($delta, -1)) {
                    $delta = $originalValue * (mb_substr($delta, 0, -1) / 100);
                }
                if (mb_strpos($newValue, '/')) {
                    $matches = [];
                    if (preg_match('/^\-([0-9]+)\/([0-9]+)$/', $newValue, $matches)) {
                        $delta = ($originalValue - $min) * $matches[1] / $matches[2];
                    }
                }

                return $originalValue - $delta;
            }
        }

        return (float) $newValue;
    }

    /**
     * Read-only access of class properties.
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
    }

    /**
     * Enables the use of conversion methods such as `toHex` from any subclass.
     * Enables the use of modifiers from any subclass.
     */
    public function __call($name, $arguments)
    {
        // Enables the use of conversion methods such as `toHex` from any subclass.
        if ('to' === mb_substr($name, 0, 2)) {
            return $this->to(mb_substr($name, 2));
        }

        // Enables the use of modifiers from any subclass.
        if (isset(self::$registeredModifiers[$name])) {
            $converter = (self::$registeredModifiers[$name])::fromRgb($this->toRgb());
            $converter = $converter->{$name}($arguments[0]);

            return static::fromRgb($converter->toRgb());
        }
    }
}
