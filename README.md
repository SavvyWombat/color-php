# SavvyWombat Color

A PHP package to help convert between and manipulate HSL and RGB colorspaces.

Main features:

- support for CSS Color Module Level 3 color specifications ( #323C46, rgb(50,60,70), hsl(210,16.7,23.5) );
- support for alpha transparency ( #323C4680, rgba(50,60,70,0.5), hsl(210,16.7,23.5,0.5) );
- modifiers to change red/green/blue or hue/saturation/lightness and alpha on any color;
- able to extend with custom colorspaces which can then be converted and/or modified;
- able to extend with custom modifiers;
- immutable behaviour.

```php
$rgb = new \SavvyWombat\Color\Rgb(50, 60, 70);
echo (string) $rgb; // rgb(50,60,70)

$hsl = $rgb->toHsl();
echo (string) $hsl; // hsl(210,16.7,23.5);

$redderHsl = $hsl->red('+50');
echo (string) $redderHsl; // hsl(345,25,31.4);

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

Color::fromString('rgb(10,20,30)'); // Rgb
Color::fromString('rgba(10,20,30,0.4)'); // Rgb

Color::fromString('hsl(10,20,30)'); // Hsl
Color::fromString('hsl(10,20,30,0.4)'); // Hsl
```

## Converting a color

Colors can be converted to any other registered color:

```php
echo (string) Color::fromString('#123')->toHsl(); // hsl(210,50,8.6)
echo (string) Color::fromString('#123')->toRgb(); // rgb(17,34,51)

echo (string) Color::fromString('rgb(25,75,125)')->toHex(); // #194B7D
echo (string) Color::fromString('rgb(25,75,125)')->toHsl(); // hsl(210,66.7,39.4)

echo (string) Color::fromString('hsl(135,50,75)')->toHex(); // #9FDFAF
echo (string) Color::fromString('hsl(135,50,75)')->toRgb(); // rgb(159,223,175)
```

## Modifying a color

The following methods are available on any registered color model by default:

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
```

Red, green, blue values are clamped in the range of 0..255. This means that adding 100 to a color which already has a red value of 200 will return a color with red set to 255.

Similarly, saturation and lightness are clamped in the range 0..100, while alpha is clamped between 0 and 1.

Hue is not clamped, as it is possible to rotate more than 360 in either direction (and 420&deg; is equivalent to 60&deg;). 

# Extending

## Custom color spaces/models

The package allows you to create your own color spaces/models. These must extend `Color`, and implement the following methods from the `ColorInterface`:

```php
    public static function fromString(string $colorSpec): ColorInterface;
    public function __toString(): string;
    public static function fromRgb(Rgb $rgb): ColorInterface;
```

For easy conversion and modification, it is recommended that you set equivalent RGB values in your class (if you're not already working in an RGB colorspace):

See below about using `fromString` and custom color specifications.

```php
class Hsl extends Color implements ColorInterface
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

        [$red, $green, $blue] = static::hslToRgb($this->hue, $this->saturation, $this->lightness);
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
    }

    ...
}
```

Alternatively, you can reimplement the default `toRgb()` method:

```php
abstract class Color
{
    public function toRgb(): Rgb
    {
        return new Rgb($this->red, $this->green, $this->blue, $this->alpha);
    }
}
```

You will need to register the color model to allow conversion to and from your new model:

```php
Color::registerColor('Cmyk', CMYK::class);

echo (string) Color::fromString('rgb(50,100,150)')->toCMYK(); // cmyk(67,33,0,41)
```

You can replace existing colors with new models.

You can get a list of registered colors via `Color::registeredColors()`.

## Custom color specifications

Once you've registered your color model, you can register color specification patterns that can be used to create that color from a string.

The patterns use regex, without the start and end delimiters. `fromString` will add the delimiters automatically. 

```php
Color::registerColor('Cmyk', Cmyk::class);
Color::registerColorSpec('cmyk\((\d{1,3}(\.\d{1,2})?),(\d{1,3}(\.\d{1,2})?),(\d{1,3}(\.\d{1,2})?),(\d{1,3}(\.\d{1,2})?)\)', Cmyk::class);
```

You can register multiple patterns and even override existing patterns to map to different classes.

You can get a list of registered patterns via `Color::registeredColorSpecs()`.

### `fromString(string $colorSpec): ColorInterface`

Your class must implement the static `fromString` method as part of the `ColorInterface` contract.

Example implementation:

```php
// cmyk\((\d{1,3}(\.\d{1,2})?),(\d{1,3}(\.\d{1,2})?),(\d{1,3}(\.\d{1,2})?),(\d{1,3}(\.\d{1,2})?)\)
public static function fromString(string $colorSpec): ColorInterface
{
    $channels = parent::extractChannels($colorSpec, self::class);

    if (empty($channels)) {
        throw new \Exception("{$colorSpec} is not a valid CMYK specification.");
    }

    return new Cmyk((float) $channels[1], (float) $channels[3], (float) $channels[5], (float) $channels[7]);
} 
```

## Custom modifiers

You can also register custom modifiers that are made available to all other models.

```php
Color::registerColor('Cmyk', Cmyk::class);
Color::registerModifier('cyan', Cmyk::class);

echo (string) Color::fromString('#102030')->cyan(50); // #182030
```

Modifiers are methods on your class, and can be written as follows:

```php
public function hue($hue): self
{
    $hue = $this->adjustValue($this->hue, $hue);

    return new self($hue, $this->saturation, $this->lightness, $this->alpha);
}
```

When you call the `hue` method directly on an `Hsl` object, it will create a new copy of the model with the updated hue value.

When you call the `hue` method on any other color object, it will first convert to `Hsl` (via an `Rgb` object), update the hue value, and convert back to an object of the original type.

In this way, it is possible to adjust the hue of an RGB color, or vary the amount of green in an HSL color.