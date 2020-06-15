<?php

declare(strict_types=1);

namespace SavvyWombat\Color\Test;

use PHPUnit\Framework\TestCase;
use SavvyWombat\Color\AbstractColor;
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

        $rgb = $baseColor->to(Rgb::class);

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
        $hex = $baseColor->to(Hex::class);

        $this->assertInstanceOf(Hex::class, $hex);
        $this->assertEquals(100, $hex->red);
        $this->assertEquals(101, $hex->green);
        $this->assertEquals(110, $hex->blue);
        $this->assertEquals(0.45, $hex->alpha);
    }

    /**
     * @test
     */
    public function can_convert_to_hsl()
    {
        $baseColor = new BaseColor(100, 101, 110, 0.45);
        $hsl = $baseColor->to(Hsl::class);

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
        $rgb = $baseColor->to(Rgb::class);

        $this->assertInstanceOf(Rgb::class, $rgb);
        $this->assertEquals(100, $rgb->red);
        $this->assertEquals(101, $rgb->green);
        $this->assertEquals(110, $rgb->blue);
        $this->assertEquals(0.45, $rgb->alpha);
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
