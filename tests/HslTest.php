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

        $this->assertEquals(16, $hsl->hue);
        $this->assertEquals(32, $hsl->saturation);
        $this->assertEquals(64, $hsl->lightness);
        $this->assertEquals(1, $hsl->alpha);
    }

    /**
     * @test
     */
    public function creates_an_hsl_color_from_an_hsla_string()
    {
        $hsl = Hsl::fromString('hsla(16,32,64,0.33)');

        $this->assertInstanceOf(Hsl::class, $hsl);

        $this->assertEquals(16, $hsl->hue);
        $this->assertEquals(32, $hsl->saturation);
        $this->assertEquals(64, $hsl->lightness);
        $this->assertEquals(0.33, $hsl->alpha);
    }

    /**
     * @test
     */
    public function creates_an_hsl_color_from_an_rgb_color()
    {
        $rgb = Rgb::fromString('rgb(48,96,192)');
        $hsl = Hsl::fromRgb($rgb);

        $this->assertInstanceOf(Hsl::class, $hsl);

        $this->assertEquals(48, $hsl->red);
        $this->assertEquals(96, $hsl->green);
        $this->assertEquals(192, $hsl->blue);
        $this->assertEquals(1, $hsl->alpha);
    }

    /**
     * @test
     */
    public function creates_an_hsl_color_from_an_rgb_color_with_alpha()
    {
        $rgb = Rgb::fromString('rgba(48,96,192,0.35)');
        $hsl = Hsl::fromRgb($rgb);

        $this->assertInstanceOf(Hsl::class, $hsl);

        $this->assertEquals(48, $hsl->red);
        $this->assertEquals(96, $hsl->green);
        $this->assertEquals(192, $hsl->blue);
        $this->assertEquals(0.35, $hsl->alpha);
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

    /**
     * @test
     */
    public function can_set_hue_to_a_new_value()
    {
        $hsl = Hsl::fromString('hsl(16,32,64)');
        $newHsl = $hsl->hue(90);

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsl(90,32,64)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_increase_hue()
    {
        $hsl = Hsl::fromString('hsl(16,32,64)');
        $newHsl = $hsl->hue('+180');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsl(196,32,64)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_decrease_hue()
    {
        $hsl = Hsl::fromString('hsl(16,32,64)');
        $newHsl = $hsl->hue('-180');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsl(-164,32,64)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_increase_hue_by_a_relative_amount()
    {
        $hsl = Hsl::fromString('hsl(180,32,64)');
        $newHsl = $hsl->hue('+50%');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsl(270,32,64)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_decrease_hue_by_a_relative_amount()
    {
        $hsl = Hsl::fromString('hsl(180,32,64)');
        $newHsl = $hsl->hue('-25%');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsl(135,32,64)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_set_saturation_to_a_new_value()
    {
        $hsl = Hsl::fromString('hsl(16,32,64)');
        $newHsl = $hsl->saturation(50);

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsl(16,50,64)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_increase_saturation_by_an_amount()
    {
        $hsl = Hsl::fromString('hsl(16,32,64)');
        $newHsl = $hsl->saturation('+32');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsl(16,64,64)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_decrease_saturation_by_an_amount()
    {
        $hsl = Hsl::fromString('hsl(16,32,64)');
        $newHsl = $hsl->saturation('-32');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsl(16,0,64)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_increase_saturation_by_a_relative_amount()
    {
        $hsl = Hsl::fromString('hsl(16,30,60)');
        $newHsl = $hsl->saturation('+10%');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsl(16,33,60)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_decrease_saturation_by_a_relative_amount()
    {
        $hsl = Hsl::fromString('hsl(16,30,64)');
        $newHsl = $hsl->saturation('-10%');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsl(16,27,64)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_set_lightness_to_a_new_value()
    {
        $hsl = Hsl::fromString('hsl(16,32,64)');
        $newHsl = $hsl->lightness(50);

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsl(16,32,50)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_lighten_by_an_amount()
    {
        $hsl = Hsl::fromString('hsl(16,32,64)');
        $newHsl = $hsl->lightness('+32');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsl(16,32,96)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_darken_by_an_amount()
    {
        $hsl = Hsl::fromString('hsl(16,32,64)');
        $newHsl = $hsl->lightness('-32');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsl(16,32,32)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_lighten_by_a_relative_amount()
    {
        $hsl = Hsl::fromString('hsl(16,32,60)');
        $newHsl = $hsl->lightness('+10%');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsl(16,32,66)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_darken_by_a_relative_amount()
    {
        $hsl = Hsl::fromString('hsl(16,32,60)');
        $newHsl = $hsl->lightness('+10%');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsl(16,32,66)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_set_alpha_to_a_new_value()
    {
        $hsl = Hsl::fromString('hsl(16,30,64)');
        $newHsl = $hsl->alpha('0.5');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsla(16,30,64,0.5)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_increase_alpha()
    {
        $hsl = Hsl::fromString('hsla(16,30,64,0.25)');
        $newHsl = $hsl->alpha('+0.5');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsla(16,30,64,0.75)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_decrease_alpha()
    {
        $hsl = Hsl::fromString('hsla(16,30,64,0.66)');
        $newHsl = $hsl->alpha('-0.5');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsla(16,30,64,0.16)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_increase_alpha_by_a_relative_amount()
    {
        $hsl = Hsl::fromString('hsla(16,30,64,0.5)');
        $newHsl = $hsl->alpha('+20%');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsla(16,30,64,0.6)', (string) $newHsl);
    }

    /**
     * @test
     */
    public function can_decrease_alpha_by_a_relative_amount()
    {
        $hsl = Hsl::fromString('hsla(16,30,64,0.5)');
        $newHsl = $hsl->alpha('-20%');

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals('hsla(16,30,64,0.4)', (string) $newHsl);
    }
}
