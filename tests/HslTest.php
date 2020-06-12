<?php

declare(strict_types=1);

namespace SavvyWombat\Color\Test;

use PHPUnit\Framework\TestCase;
use SavvyWombat\Color\Hsl;
use SavvyWombat\Color\Rgb;

class HslTest extends TestCase
{
    /**
     * @test
     */
    public function creates_an_hsl_color_from_an_hsl_string()
    {
        $hsl = Hsl::fromString('hsl(16,32,64)');

        $this->assertInstanceOf(Hsl::class, $hsl);

        $this->assertEquals(16, $hsl->hue());
        $this->assertEquals(32, $hsl->saturation());
        $this->assertEquals(64, $hsl->lightness());
        $this->assertEquals(1, $hsl->alpha());
    }

    /**
     * @test
     */
    public function creates_an_hsl_color_from_an_hsla_string()
    {
        $hsl = Hsl::fromString('hsla(16,32,64,0.33)');

        $this->assertInstanceOf(Hsl::class, $hsl);

        $this->assertEquals(16, $hsl->hue());
        $this->assertEquals(32, $hsl->saturation());
        $this->assertEquals(64, $hsl->lightness());
        $this->assertEquals(0.33, $hsl->alpha());
    }

    /**
     * @test
     */
    public function creates_an_hsl_color_from_an_rgb_color()
    {
        $rgb = Rgb::fromString('rgb(48,96,192)');
        $hsl = Hsl::fromRgb($rgb);

        $this->assertInstanceOf(Hsl::class, $hsl);

        $this->assertEquals(48, $hsl->red());
        $this->assertEquals(96, $hsl->green());
        $this->assertEquals(192, $hsl->blue());
        $this->assertEquals(1, $hsl->alpha());
    }

    /**
     * @test
     */
    public function creates_an_hsl_color_from_an_rgb_color_with_alpha()
    {
        $rgb = Rgb::fromString('rgba(48,96,192,0.35)');
        $hsl = Hsl::fromRgb($rgb);

        $this->assertInstanceOf(Hsl::class, $hsl);

        $this->assertEquals(48, $hsl->red());
        $this->assertEquals(96, $hsl->green());
        $this->assertEquals(192, $hsl->blue());
        $this->assertEquals(0.35, $hsl->alpha());
    }

    /**
     * @test
     */
    public function castable_to_string()
    {
        $hsl = Hsl::fromString('hsl(16,32,64)');

        $this->assertEquals('hsl(16,32,64)', (string) $hsl);
    }

    /**
     * @test
     */
    public function castable_to_string_with_alpha_channel()
    {
        $hsl = Hsl::fromString('hsla(16,32,64,0.5)');

        $this->assertEquals('hsla(16,32,64,0.5)', (string) $hsl);
    }
}
