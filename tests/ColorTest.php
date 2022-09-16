<?php

declare(strict_types=1);

namespace SavvyWombat\Color\Test;

use PHPUnit\Framework\TestCase;
use SavvyWombat\Color\Color;
use SavvyWombat\Color\ColorInterface;
use SavvyWombat\Color\Exception;
use SavvyWombat\Color\Hex;
use SavvyWombat\Color\Hsl;
use SavvyWombat\Color\Rgb;

class ColorTest extends TestCase
{
    /**
     * @test
     */
    public function creates_an_rgb_color_with_alpha(): void
    {
        $color = new Gray(50, 0.45);

        $rgb = $color->toRgb();

        $this->assertInstanceOf(Rgb::class, $rgb);
        $this->assertEquals(128, round($rgb->red));
        $this->assertEquals(128, round($rgb->green));
        $this->assertEquals(128, round($rgb->blue));
        $this->assertEquals(0.45, round($rgb->alpha, 2));
    }

    /**
     * @test
     *
     * @dataProvider valid_hex_names
     */

    /**
     * @test
     *
     * @dataProvider valid_hex_names
     */
    public function creates_a_hex_color_from_a_name($name, $value): void
    {
        $color = Color::fromString($name);

        $this->assertInstanceOf(Hex::class, $color);
        $this->assertEquals($value, (string) $color);
    }

    /**
     * @test
     */
    public function cannot_convert_to_unregistered_color(): void
    {
        $this->expectException(Exception::class);

        $color = new Gray(50, 0.45);
        $color->toUnregistered();
    }

    /**
     * @test
     */
    public function can_convert_to_hex(): void
    {
        $color = new Gray(50, 0.45);
        $hex = $color->toHex();

        $this->assertInstanceOf(Hex::class, $hex);
        $this->assertEquals(128, $hex->red);
        $this->assertEquals(128, $hex->green);
        $this->assertEquals(128, $hex->blue);
        $this->assertEquals(0.45, round($hex->alpha, 2));
    }

    /**
     * @test
     */
    public function can_convert_to_hsl(): void
    {
        $color = new Gray(50, 0.45);
        $hsl = $color->toHsl();

        $this->assertInstanceOf(Hsl::class, $hsl);
        $this->assertEquals(128, round($hsl->red));
        $this->assertEquals(128, round($hsl->green));
        $this->assertEquals(128, round($hsl->blue));
        $this->assertEquals(0.45, round($hsl->alpha, 2));
    }

    /**
     * @test
     */
    public function can_convert_to_rgb(): void
    {
        $color = new Gray(50, 0.45);
        $rgb = $color->toRgb();

        $this->assertInstanceOf(Rgb::class, $rgb);
        $this->assertEquals(128, round($rgb->red));
        $this->assertEquals(128, round($rgb->green));
        $this->assertEquals(128, round($rgb->blue));
        $this->assertEquals(0.45, round($rgb->alpha, 2));
    }

    /**
     * @test
     */
    public function can_register_new_color(): void
    {
        Color::registerColor('Gray', Gray::class);

        $registeredColors = Color::registeredColors();

        $this->assertArrayHasKey('Gray', $registeredColors);
        $this->assertEquals(Gray::class, $registeredColors['Gray']);
    }

    /**
     * @test
     */
    public function color_class_must_exist(): void
    {
        $this->expectException(Exception::class);

        Color::registerColor('Gray', 'DoesNotExist');
    }

    /**
     * @test
     */
    public function colors_must_extend_abstract(): void
    {
        $this->expectException(Exception::class);

        Color::registerColor('Gray', DoesNotExtendAbstract::class);
    }

    /**
     * @test
     */
    public function can_convert_to_new_color(): void
    {
        $rgb = new Rgb(25, 50, 75, 0.5);

        Color::registerColor('Gray', Gray::class);

        $gray = $rgb->toGray();
        $this->assertInstanceOf(Gray::class, $gray);
        $this->assertEquals(19.6, round($gray->value, 1));
        $this->assertEquals(0.5, $gray->alpha);
    }

    /**
     * @test
     */
    public function can_convert_to_new_color_from_any_other(): void
    {
        $hsl = new Hsl(25, 50, 75, 0.5);

        Color::registerColor('Gray', Gray::class);

        $gray = $hsl->toGray();
        $this->assertInstanceOf(Gray::class, $gray);
        $this->assertEquals(74.3, round($gray->value, 1));
        $this->assertEquals(0.5, $gray->alpha);
    }

    /**
     * @test
     */
    public function color_for_spec_must_be_registered(): void
    {
        $this->expectException(Exception::class);

        Color::registerColorSpec('gray\((\d{1,3}(\.\d{1})?)%\)', Gray::class);
    }

    /**
     * @test
     */
    public function new_color_spec_can_be_used(): void
    {
        Color::registerColor('Gray', Gray::class);
        Color::registerColorSpec('gray\((\d{1,3}(\.\d{1})?)%\)', Gray::class);

        $gray = Gray::fromString('gray(50%)');

        $this->assertInstanceOf(Gray::class, $gray);
        $this->assertEquals('gray(50%)', (string) $gray);
    }

    /**
     * @test
     */
    public function new_color_spec_cannot_be_used_on_wrong_color(): void
    {
        $this->expectException(Exception::class);

        Color::registerColor('Gray', Gray::class);
        Color::registerColorSpec('gray\((\d{1,3}(\.\d{1})?)%\)', Gray::class);

        Rgb::fromString('gray(50%)');
    }

    /**
     * @test
     */
    public function new_color_spec_can_be_used_by_the_abstract(): void
    {
        Color::registerColor('Gray', Gray::class);
        Color::registerColorSpec('gray\((\d{1,3}(\.\d{1})?)%\)', Gray::class);

        $gray = Color::fromString('gray(50%)');

        $this->assertInstanceOf(Gray::class, $gray);
        $this->assertEquals('gray(50%)', (string) $gray);
    }

    /**
     * @test
     */
    public function can_register_new_color_modifier(): void
    {
        Color::registerColor('Gray', Gray::class);
        Color::registerModifier('gray', Gray::class);

        $rgb = Rgb::fromString('rgb(25, 50, 75)');
        $newRgb = $rgb->gray('25');

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($rgb, $newRgb);
        $this->assertEquals('rgb(64,64,64)', (string) $newRgb);
    }

    /**
     * @test
     */
    public function color_for_modifier_must_be_registered(): void
    {
        $this->expectException(Exception::class);

        Color::registerModifier('gray', Gray::class);
    }

    /**
     * @test
     */
    public function modifier_method_must_exist(): void
    {
        $this->expectException(Exception::class);

        Color::registerModifier('doesNotExist', Gray::class);
    }

    /**
     * @test
     */
    public function adjust_value_returns_absolute_value(): void
    {
        $color = new Gray(50);

        $this->assertEquals(100, $color->adjustValue(50, '100'));
    }

    /**
     * @test
     */
    public function adjust_value_increases_original_value(): void
    {
        $color = new Gray(50);

        $this->assertEquals(60, $color->adjustValue(50, '+10'));
    }

    /**
     * @test
     */
    public function adjust_value_decreases_original_value(): void
    {
        $color = new Gray(50);

        $this->assertEquals(40, $color->adjustValue(50, '-10'));
    }

    /**
     * @test
     */
    public function adjust_value_increases_by_percentage(): void
    {
        $color = new Gray(50);

        $this->assertEquals(55, $color->adjustValue(50, '+10%'));
    }

    /**
     * @test
     */
    public function adjust_value_decreases_by_percentage(): void
    {
        $color = new Gray(50);

        $this->assertEquals(45, $color->adjustValue(50, '-10%'));
    }

    /**
     * @test
     */
    public function adjust_value_increase_by_fraction(): void
    {
        $color = new Gray(50);

        $this->assertEquals(75, $color->adjustValue(50, '+1/2', 100));
        $this->assertEquals(152.5, $color->adjustValue(50, '+1/2', 255));
    }

    /**
     * @test
     */
    public function adjust_value_decrease_by_fraction(): void
    {
        $color = new Gray(50);

        $this->assertEquals(16.67, round($color->adjustValue(50, '-2/3', 100), 2));
        $this->assertEquals(30, round($color->adjustValue(50, '-2/3', 100, 20), 2));
    }

    public function valid_hex_names()
    {
        return [
            'black' => ['black', '#000000'],
            'papayawhip' => ['papayawhip', '#ffefd5'],
            'wheat' => ['wheat', '#f5deb3'],
        ];
    }
}

abstract class DoesNotImplementInterface extends Color
{
}
abstract class DoesNotExtendAbstract implements ColorInterface
{
}

class Gray extends Color implements ColorInterface
{
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

    public static function fromString(string $colorSpec): ColorInterface
    {
        $channels = Color::extractChannels($colorSpec, self::class);

        if ( ! isset($channels[3])) {
            $channels[3] = 1.0;
        }

        return new Gray((float) $channels[1], $channels[3]);
    }

    public function __toString(): string
    {
        $value = round($this->value, 1);
        $alpha = round($this->alpha, 2);

        if (1.0 === $alpha) {
            return "gray({$value}%)";
        }

        return "gray({$value}%,{$alpha})";
    }

    public static function fromRgb(Rgb $rgb): ColorInterface
    {
        $average = ($rgb->red + $rgb->green + $rgb->blue) / 3;
        $gray = $average * 100 / 255;

        return new Gray($gray, $rgb->alpha);
    }

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
}
