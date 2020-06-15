<?php

declare(strict_types=1);

namespace SavvyWombat\Color\Test;

use PHPUnit\Framework\TestCase;
use SavvyWombat\Color\Rgb;

class RgbTest extends TestCase
{
    /**
     * @test
     */
    public function creates_an_rgb_color_from_an_rgb_string()
    {
        $rgb = Rgb::fromString('rgb(16,32,64)');

        $this->assertInstanceOf(Rgb::class, $rgb);

        $this->assertEquals(16, $rgb->red);
        $this->assertEquals(32, $rgb->green);
        $this->assertEquals(64, $rgb->blue);
        $this->assertEquals(1, $rgb->alpha);
    }

    /**
     * @test
     */
    public function creates_an_rgb_color_from_an_rgba_string()
    {
        $rgb = Rgb::fromString('rgba(16,32,64,0.33)');

        $this->assertInstanceOf(Rgb::class, $rgb);

        $this->assertEquals(16, $rgb->red);
        $this->assertEquals(32, $rgb->green);
        $this->assertEquals(64, $rgb->blue);
        $this->assertEquals(0.33, $rgb->alpha);
    }

    /**
     * @test
     */
    public function creates_a_new_rgb_color_from_an_rgb_color()
    {
        $rgb = Rgb::fromString('rgb(48,96,192)');
        $newRgb = Rgb::fromRgb($rgb);

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($newRgb, $rgb);

        $this->assertEquals(48, $newRgb->red);
        $this->assertEquals(96, $newRgb->green);
        $this->assertEquals(192, $newRgb->blue);
        $this->assertEquals(1, $newRgb->alpha);
    }

    /**
     * @test
     */
    public function creates_a_new_rgb_color_from_an_rgb_color_with_alpha()
    {
        $rgb = Rgb::fromString('rgba(48,96,192,0.75)');
        $newRgb = Rgb::fromRgb($rgb);

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($newRgb, $rgb);

        $this->assertEquals(48, $newRgb->red);
        $this->assertEquals(96, $newRgb->green);
        $this->assertEquals(192, $newRgb->blue);
        $this->assertEquals(0.75, $newRgb->alpha);
    }

    /**
     * @test
     */
    public function castable_to_string()
    {
        $rgb = Rgb::fromString('rgb(32,64,128)');

        $this->assertEquals('rgb(32,64,128)', (string) $rgb);
    }

    /**
     * @test
     */
    public function castable_to_string_with_alpha_channel()
    {
        $rgb = Rgb::fromString('rgba(32,64,128,0.67)');

        $this->assertEquals('rgba(32,64,128,0.67)', (string) $rgb);
    }

    /**
     * @test
     */
    public function can_set_alpha_to_a_new_value()
    {
        $rgb = Rgb::fromString('rgb(16,30,64)');
        $newRgb = $rgb->alpha('0.5');

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($rgb, $newRgb);
        $this->assertEquals('rgba(16,30,64,0.5)', (string) $newRgb);
    }

    /**
     * @test
     */
    public function can_increase_alpha()
    {
        $rgb = Rgb::fromString('rgba(16,30,64,0.25)');
        $newRgb = $rgb->alpha('+0.5');

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($rgb, $newRgb);
        $this->assertEquals('rgba(16,30,64,0.75)', (string) $newRgb);
    }

    /**
     * @test
     */
    public function can_decrease_alpha()
    {
        $rgb = Rgb::fromString('rgba(16,30,64,0.66)');
        $newRgb = $rgb->alpha('-0.5');

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($rgb, $newRgb);
        $this->assertEquals('rgba(16,30,64,0.16)', (string) $newRgb);
    }

    /**
     * @test
     */
    public function can_increase_alpha_by_a_relative_amount()
    {
        $rgb = Rgb::fromString('rgba(16,30,64,0.5)');
        $newRgb = $rgb->alpha('+20%');

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($rgb, $newRgb);
        $this->assertEquals('rgba(16,30,64,0.6)', (string) $newRgb);
    }

    /**
     * @test
     */
    public function can_decrease_alpha_by_a_relative_amount()
    {
        $rgb = Rgb::fromString('rgba(16,30,64,0.5)');
        $newRgb = $rgb->alpha('-20%');

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($rgb, $newRgb);
        $this->assertEquals('rgba(16,30,64,0.4)', (string) $newRgb);
    }
}
