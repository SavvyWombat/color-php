<?php

declare(strict_types=1);

namespace SavvyWombat\Color\Test;

use PHPUnit\Framework\TestCase;
use SavvyWombat\Color\AbstractColor;
use SavvyWombat\Color\ColorInterface;
use SavvyWombat\Color\Hex;
use SavvyWombat\Color\Hsl;
use SavvyWombat\Color\InvalidColorException;
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
        $this->expectException(InvalidColorException::class);

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
        $this->expectException(InvalidColorException::class);

        AbstractColor::registerColor('Gray', 'DoesNotExist');
    }

    /**
     * @test
     */
    public function colors_must_implement_interface()
    {
        $this->expectException(InvalidColorException::class);

        AbstractColor::registerColor('Gray', Whatever::class);
    }

    /**
     * @test
     */
    public function colors_must_extend_abstract()
    {
        $this->expectException(InvalidColorException::class);

        AbstractColor::registerColor('Gray', Whatever::class);
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
abstract class DoesNotExtendAbsract implements ColorInterface {}
abstract class Gray extends AbstractColor implements ColorInterface {}