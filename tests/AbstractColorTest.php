<?php

declare(strict_types=1);

namespace SavvyWombat\Color\Test;

use PHPUnit\Framework\TestCase;
use SavvyWombat\Color\AbstractColor;
use SavvyWombat\Color\ColorInterface;
use SavvyWombat\Color\Hex;
use SavvyWombat\Color\Hsl;
use SavvyWombat\Color\Exception;
use SavvyWombat\Color\Rgb;

class AbstractColorTest extends TestCase
{
    /**
     * @test
     */
    public function creates_an_rgb_color_with_alpha()
    {
        $baseColor = new BaseColor(100, 101, 110, 0.45);

        $rgb = $baseColor->toRgb();

        $this->assertInstanceOf(Rgb::class, $rgb);
        $this->assertEquals(100, $rgb->red);
        $this->assertEquals(101, $rgb->green);
        $this->assertEquals(110, $rgb->blue);
        $this->assertEquals(0.45, $rgb->alpha);
    }

    /**
     * @test
     */
    public function cannot_convert_to_unsupported_color()
    {
        $this->expectException(Exception::class);

        $baseColor = new BaseColor(100, 101, 110, 0.45);
        $baseColor->to(BaseColor::class);
    }

    /**
     * @test
     */
    public function can_convert_to_hex()
    {
        $baseColor = new BaseColor(100, 101, 110, 0.45);
        $hex = $baseColor->toHex();

        $this->assertInstanceOf(Hex::class, $hex);
        $this->assertEquals(100, $hex->red);
        $this->assertEquals(101, $hex->green);
        $this->assertEquals(110, $hex->blue);
        $this->assertEquals(0.45, round($hex->alpha, 2));
    }

    /**
     * @test
     */
    public function can_convert_to_hsl()
    {
        $baseColor = new BaseColor(100, 101, 110, 0.45);
        $hsl = $baseColor->toHsl();

        $this->assertInstanceOf(Hsl::class, $hsl);
        $this->assertEquals(100, $hsl->red);
        $this->assertEquals(101, $hsl->green);
        $this->assertEquals(110, $hsl->blue);
        $this->assertEquals(0.45, $hsl->alpha);
    }

    /**
     * @test
     */
    public function can_convert_to_rgb()
    {
        $baseColor = new BaseColor(100, 101, 110, 0.45);
        $rgb = $baseColor->toRgb();

        $this->assertInstanceOf(Rgb::class, $rgb);
        $this->assertEquals(100, $rgb->red);
        $this->assertEquals(101, $rgb->green);
        $this->assertEquals(110, $rgb->blue);
        $this->assertEquals(0.45, $rgb->alpha);
    }

    /**
     * @test
     */
    public function can_register_new_color()
    {
        AbstractColor::registerColor('Gray', Gray::class);

        $registeredColors = AbstractColor::registeredColors();

        $this->assertArrayHasKey('Gray', $registeredColors);
        $this->assertEquals(Gray::class, $registeredColors['Gray']);
    }

    /**
     * @test
     */
    public function color_class_must_exist()
    {
        $this->expectException(Exception::class);

        AbstractColor::registerColor('Gray', 'DoesNotExist');
    }

    /**
     * @test
     */
    public function colors_must_implement_interface()
    {
        $this->expectException(Exception::class);

        AbstractColor::registerColor('Gray', DoesNotImplementInterface::class);
    }

    /**
     * @test
     */
    public function colors_must_extend_abstract()
    {
        $this->expectException(Exception::class);

        AbstractColor::registerColor('Gray', DoesNotExtendAbstract::class);
    }

    /**
     * @test
     */
    public function can_convert_to_new_color()
    {
        $rgb = new Rgb(25, 50, 75, 0.5);

        AbstractColor::registerColor('Gray', Gray::class);

        $gray = $rgb->toGray();
        $this->assertInstanceOf(Gray::class, $gray);
        $this->assertEquals(50, $gray->value);
        $this->assertEquals(0.5, $gray->alpha);
    }

    /**
     * @test
     */
    public function can_convert_to_new_color_from_any_other()
    {
        $hsl = new Hsl(25, 50, 75, 0.5);

        AbstractColor::registerColor('Gray', Gray::class);

        $gray = $hsl->toGray();
        $this->assertInstanceOf(Gray::class, $gray);
        $this->assertEquals(189, round($gray->value));
        $this->assertEquals(0.5, $gray->alpha);
    }

    /**
     * @test
     */
    public function can_register_new_color_spec_patterns()
    {
        AbstractColor::registerColor('Gray', Gray::class);
        AbstractColor::registerColorSpec('gray\((\d{1,3})\)', Gray::class);

        $registeredColorSpecs = AbstractColor::registeredColorSpecs();

        $this->assertArrayHasKey('gray\((\d{1,3})\)', $registeredColorSpecs);
        $this->assertEquals(Gray::class, $registeredColorSpecs['gray\((\d{1,3})\)']);
    }

    /**
     * @test
     */
    public function color_for_spec_must_be_registered()
    {
        $this->expectException(Exception::class);

        AbstractColor::registerColorSpec('gray\((\d{1,3})\)', Gray::class);
    }

    /**
     * @test
     */
    public function new_color_spec_can_be_used()
    {
        AbstractColor::registerColor('Gray', Gray::class);
        AbstractColor::registerColorSpec('gray\((\d{1,3})\)', Gray::class);

        $gray = Gray::fromString('gray(50)');

        $this->assertInstanceOf(Gray::class, $gray);
    }

    /**
     * @test
     */
    public function new_color_spec_cannot_be_used_on_wrong_color()
    {
        $this->expectException(Exception::class);

        AbstractColor::registerColor('Gray', Gray::class);
        AbstractColor::registerColorSpec('gray\((\d{1,3})\)', Gray::class);

        Rgb::fromString('gray(50)');
    }

    /**
     * @test
     */
    public function new_color_spec_can_be_used_by_the_abstract()
    {
        AbstractColor::registerColor('Gray', Gray::class);
        AbstractColor::registerColorSpec('gray\((\d{1,3})\)', Gray::class);

        $gray = AbstractColor::fromString('gray(50)');

        $this->assertInstanceOf(Gray::class, $gray);
    }

    /**
     * @test
     */
    public function can_register_new_color_modifier()
    {
        AbstractColor::registerColor('Gray', Gray::class);
        AbstractColor::registerModifier('lightness', Gray::class);

        $rgb = Rgb::fromString('rgb(25,50,75)');
        $newRgb = $rgb->lightness(100);

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($rgb, $newRgb);
        $this->assertEquals('rgb(100,100,100)', (string) $newRgb);
    }

    /**
     * @test
     */
    public function color_for_modifier_must_be_registered()
    {
        $this->expectException(Exception::class);

        AbstractColor::registerModifier('lightness', Gray::class);
    }

    /**
     * @test
     */
    public function modifier_method_must_exist()
    {
        $this->expectException(Exception::class);

        AbstractColor::registerModifier('doesNotExists', Gray::class);
    }
}

class BaseColor extends AbstractColor
{
    public function __construct($red, $green, $blue, $alpha)
    {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->alpha = round($alpha, 2);
    }
}

abstract class DoesNotImplementInterface extends AbstractColor {}
abstract class DoesNotExtendAbstract implements ColorInterface {}

class Gray extends AbstractColor implements ColorInterface
{
    protected $value;

    public function __construct(float $value, float $alpha = 1.0)
    {
        $this->value = $value;
        $this->alpha = $alpha;

        $this->red = $value;
        $this->green = $value;
        $this->blue = $value;
    }

    public static function fromString(string $colorSpec): ColorInterface
    {
        $channels = parent::extractChannels($colorSpec, self::class);

        return new Gray((float) $channels[1]);
    }

    public function __toString(): string
    {
        $value = round($this->value);

        return "gray({$value})`";
    }

    public static function fromRgb(Rgb $rgb): ColorInterface
    {
        return new Gray(($rgb->red + $rgb->green + $rgb->blue) / 3, $rgb->alpha);
    }

    public function lightness($value): ColorInterface
    {
        return new Gray($value, $this->alpha);
    }
}