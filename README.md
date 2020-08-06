# SavvyWombat Color

[![Latest Version on Packagist](https://img.shields.io/packagist/v/savvywombat/color.svg)](https://packagist.org/packages/savvywombat/color)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Build](https://img.shields.io/github/workflow/status/SavvyWombat/color-php/Test?label=build)](https://github.com/SavvyWombat/color-php/actions)
[![Code Coverage](https://codecov.io/gh/SavvyWombat/color-php/branch/main/graph/badge.svg)](https://codecov.io/gh/SavvyWombat/color-php)

A PHP package to help convert between and manipulate HSL and RGB colorspaces/types.

Main features:

- support for CSS Color Module Level 3 color specifications ( #323C46, rgb(50,60,70), hsl(210,16.7%,23.5%) );
- support for alpha transparency ( #323C4680, rgba(50,60,70,0.5), hsla(210,16.7%,23.5%,0.5) );
- modifiers to change red/green/blue or hue/saturation/lightness and alpha on any color type;
- able to extend with custom colorspaces/types which can then be converted and/or modified;
- able to extend with custom modifiers;
- immutable behaviour.

```php
$rgb = new \SavvyWombat\Color\Rgb(50, 60, 70);
echo (string) $rgb; // rgb(50,60,70)

$hsl = $rgb->toHsl();
echo (string) $hsl; // hsl(210,16.7%,23.5%);

$redderHsl = $hsl->red('+50');
echo (string) $redderHsl; // hsl(345,25%,31.4%);

$lighterRgb = $rgb->lighten('+10%');
echo (string) $lighterRgb; // rgb(55,66,77);

$transparentRgb = $rgb->alpha('0.5');
echo (string) $transparentRgb; // rgb(50,60,70,0.5)

echo (string) $rgb->toHex(); // #323C46
```

# Installation

```
composer require savvywombat\color
```

# Usage

## Creating a color

The following patterns are available by default to create a color:

```php
Color::fromString('#123'); // Hex: #112233
Color::fromString('#1234'); // Hex (with alpha): #11223344
Color::fromString('#123456'); // Hex: #123456
Color::fromString('#12345678'); // Hex: #12345678

Color::fromString('rgb(10, 20, 30)'); // Rgb
Color::fromString('rgba(10, 20, 30, 0.4)'); // Rgb

Color::fromString('hsl(10, 20%, 30%)'); // Hsl
Color::fromString('hsl(10, 20%, 30%, 0.4)'); // Hsl
```

## Converting a color

Colors can be converted to any other registered color type:

```php
echo (string) Color::fromString('#123')->toHsl(); // hsl(210,50%,8.6%)
echo (string) Color::fromString('#123')->toRgb(); // rgb(17,34,51)

echo (string) Color::fromString('rgb(25, 75, 125)')->toHex(); // #194B7D
echo (string) Color::fromString('rgb(25, 75, 125)')->toHsl(); // hsl(210,66.7%,39.4%)

echo (string) Color::fromString('hsl(135, 50%, 75%)')->toHex(); // #9FDFAF
echo (string) Color::fromString('hsl(135, 50%, 75%)')->toRgb(); // rgb(159,223,175)
```

## Modifying a color

The following methods are available on any registered color type by default:

- `red($value)`
- `green($value)`
- `blue($value)`
- `alpha($value)`
- `hue($value)`
- `saturation($value)`
- `lightness($value)`

All methods return a modified copy of the original object, leaving the original unmodified, and will accept the following argument formats:

```php
$newHsl = $hsl->red(50); // set to specific value
$newHsl = $hsl->red('+50'); // add 50 to the current value
$newHsl = $hsl->red('-50'); // remove 50 from the current value
$newHsl = $hsl->red('+50%'); // increase the current value by 50%
$newHsl = $hsl->red('-50%'); // decrease the current value by 50%
$newHsl = $hsl->red('+1/2'); // set to a value halfway between the current value and 255 (maximum red value)
$newHsl = $hsl->red('-1/2'); // set to a value halfway between the current value and 0 (minimum red value)
```

Red, green, blue values are clamped in the range of 0..255. This means that adding 100 to a color which already has a red value of 200 will return a color with red set to 255.

Similarly, saturation and lightness are clamped in the range 0..100, while alpha is clamped between 0 and 1.

Hue is not clamped, as it is possible to rotate more than 360 in either direction (and 420&deg; is equivalent to 60&deg;). Because of this, the fractional pattern ('+1/2') should not be used as there is no minimum or maximum. 

# Extending

## Custom color types

The package allows you to create your own color types. These must extend `Color`, and implement the following methods from the `ColorInterface`:

```php
    public static function fromString(string $colorSpec): ColorInterface;
    public function __toString(): string;

    public static function fromRgb(Rgb $rgb): ColorInterface;
    public function toRgb(): Rgb;
```

#### Registering color types

You will need to register the class to allow conversion to and from your new color:

```php
Color::registerColor('Gray', Gray::class);
echo (string) Color::fromString('#163248')->toGray(); // #323232
```

You can replace existing color types with new ones.

You can get a list of registered color types via `Color::registeredColors()`.

### `public static function fromString(string $colorSpec): ColorInterface`

```php
echo (string) Color::fromString('#204060')->toGray(); // gray(25%)
echo (string) Color::fromString('gray(50%, 0.25)')->toRgb(); // #80808040
```

The `fromString` accepts a color specification and tests it against the registered patterns and extracts the information.

```php
/**
 * color spec pattern: 'gray\((\d{1,3}(\.\d{1})?)%\)'
 * color spec pattern: 'gray\((\d{1,3}(\.\d{1})?)%,([0-1](\.\d{1,2})?)\)'
 *
 * If the color spec matches either of the patterns, extractChannels will return the parameters from the string using regex.
 */
public static function fromString(string $colorSpec): ColorInterface
{
    $channels = Color::extractChannels($colorSpec, self::class);

    if (!isset($channels[3])) {
        $channels[3] = 1.0;
    }

    return new Gray((float) $channels[1], $channels[3]);
}
```

#### Registering color specification patterns

To register a color specification pattern you must first register the color type it will apply to:

```php
Color::registerColor('Gray', Gray::class);
Color::registerColorSpec('gray\((\d{1,3})\)', Gray::class);
```

You register new specifications for any registered color type, including the default types. You can also override registered patterns so that they apply to different color types.

You can get a list of registered color specification via `Color::registeredColorSpecs()`.

### `public function __toString(): string;`

```php
public function __toString(): string
{
    $value = round($this->value, 1);
    $alpha = round($this->alpha, 2);
    
    if ($alpha === 1.0) {
        return "gray({$value},{$alpha})";
    }

    return "gray({$value})";
}
```

### `public static function fromRgb(Rgb $rgb): ColorInterface`

This method is used to allow conversion from one color type to any another that has been registered. This means that you only have to specify how to convert _to_ your new type _from_ an RGB color.

```php
public static function fromRgb(Rgb $rgb): ColorInterface
{
    $average = ($rgb->red + $rgb->green + $rgb->blue) / 3;
    $gray = $average * 100 / 255;

    return new Gray($gray, $rgb->alpha);
}
```

### `public function toRgb(): Rgb`

The abstract `\SavvyWombat\Color\Color` already implements this method:

```php
public function toRgb(): Rgb
{
    return new Rgb($this->red, $this->green, $this->blue, $this->alpha);
}
```

The red, green, blue and alpha properties are also defined on the abstract. You can either reimplement the method, or set these values in your color type constructor to make use of the default implementation:

```php
use \SavvyWombat\Color\Color;

class Gray extends Color
{
    // gray value in range 0..100
    protected $value;

    public function __construct(float $value, float $alpha = 1.0)
    {
        $this->value = $value;
        $this->alpha = $alpha;

        // calculate RGB equivalent values for easy conversion
        $this->red = $value * 2.55; // convert to 0..255 for RGB
        $this->green = $value * 2.55;
        $this->blue = $value * 2.55;
    }
}
```

It is recommended that you use the default implementation, as the red, green and blue properties are exposed in the public interface.

## Custom color modifiers

Color modifiers are methods on color types that are registered and made available to all other color types.

Calling a modifier on a color type will convert it to the type that implements the registered method, and then convert the result back to the original type. To help with this implicit conversion, custom modifiers must return an object which extends `Color` - ideally, it should be a copy of the same type the method is implemented on.

```php
public function gray($gray): self
{
    $gray = $this->adjustValue($this->gray, $gray, 100);
    
    if ($gray < 0) {
        $gray = 0;
    }

    if ($gray > 100) {
        $gray = 100;
    }

    return new self($gray, $this->alpha);
}
```

`Color::adjustValue($originalValue, $newValue, $max = 0, $min = 0)` handles the various relative arguments (&plusmn;50 or &plusmn;50%) to produce the new calculated value for the property being modified.

The modifier is also responsible for ensuring the new value is valid for the property being modified.

## Support for color keywords

```Color::fromString()``` and ```Hex::fromString()``` will accept any of the CSS color keywords to create a Hex color.

If you wish to generate a color of a different type from a keyword, you can convert from the Hex color after creation:

```php
$hsl = Color::fromString('deepskyblue')->toHsl();
```

# License

The MIT License (MIT). Please see [License File](LICENSE) for more information.