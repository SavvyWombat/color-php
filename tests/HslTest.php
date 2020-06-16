<?php

declare(strict_types=1);

namespace SavvyWombat\Color\Test;

use PHPUnit\Framework\TestCase;
use SavvyWombat\Color\Hsl;
use SavvyWombat\Color\InvalidColorException;
use SavvyWombat\Color\Rgb;

class HslTest extends TestCase
{
    /**
     * @test
     * @dataProvider valid_color_strings
     */
    public function creates_an_hsl_color_from_a_string($colorSpec, $hue, $saturation, $lightness, $alpha)
    {
        $hsl = Hsl::fromString($colorSpec);

        $this->assertInstanceOf(Hsl::class, $hsl);

        $this->assertEquals($hue, $hsl->hue);
        $this->assertEquals($saturation, $hsl->saturation);
        $this->assertEquals($lightness, $hsl->lightness);
        $this->assertEquals($alpha, $hsl->alpha);
    }

    /**
     * @test
     * @dataProvider invalid_color_strings
     */
    public function throws_an_error_if_provided_an_invalid_color_string($colorSpec)
    {
        $this->expectException(InvalidColorException::class);

        Hsl::fromString($colorSpec);
    }

    /**
     * @test
     * @dataProvider invalid_color_values
     */
    public function throws_an_error_if_passed_invalid_color_values($hue, $saturation, $lightness, $alpha)
    {
        $this->expectException(InvalidColorException::class);

        new Hsl($hue, $saturation, $lightness, $alpha);
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
     * @dataProvider modify_red_channel
     */
    public function can_modify_red_channel($initialColor, $newRedValue, $result)
    {
        $hsl = Hsl::fromString($initialColor);
        $newHsl = $hsl->red($newRedValue);

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals($initialColor, (string) $hsl);
        $this->assertEquals($result, (string) $newHsl);
    }

    /**
     * @test
     * @dataProvider modify_green_channel
     */
    public function can_modify_green_channel($initialColor, $newGreenValue, $result)
    {
        $hsl = Hsl::fromString($initialColor);
        $newHsl = $hsl->green($newGreenValue);

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals($initialColor, (string) $hsl);
        $this->assertEquals($result, (string) $newHsl);
    }

    /**
     * @test
     * @dataProvider modify_blue_channel
     */
    public function can_modify_blue_channel($initialColor, $newBlueValue, $result)
    {
        $hsl = Hsl::fromString($initialColor);
        $newHsl = $hsl->blue($newBlueValue);

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals($initialColor, (string) $hsl);
        $this->assertEquals($result, (string) $newHsl);
    }

    /**
     * @test
     * @dataProvider modify_alpha_channel
     */
    public function can_modify_alpha_channel($initialColor, $newAlphaValue, $result)
    {
        $hsl = Hsl::fromString($initialColor);
        $newHsl = $hsl->alpha($newAlphaValue);

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals($initialColor, (string) $hsl);
        $this->assertEquals($result, (string) $newHsl);
    }

    /**
     * @test
     * @dataProvider modify_hue
     */
    public function can_modify_hue($initialColor, $newHueValue, $result)
    {
        $hsl = Hsl::fromString($initialColor);
        $newHsl = $hsl->hue($newHueValue);

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals($initialColor, (string) $hsl);
        $this->assertEquals($result, (string) $newHsl);
    }

    /**
     * @test
     * @dataProvider modify_saturation
     */
    public function can_modify_saturation($initialColor, $newSaturationValue, $result)
    {
        $hsl = Hsl::fromString($initialColor);
        $newHsl = $hsl->saturation($newSaturationValue);

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals($initialColor, (string) $hsl);
        $this->assertEquals($result, (string) $newHsl);
    }

    /**
     * @test
     * @dataProvider modify_lightness
     */
    public function can_modify_lightness($initialColor, $newLightnessValue, $result)
    {
        $hsl = Hsl::fromString($initialColor);
        $newHsl = $hsl->lightness($newLightnessValue);

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals($initialColor, (string) $hsl);
        $this->assertEquals($result, (string) $newHsl);
    }

    public function valid_color_strings()
    {
        return [
            'hsl(16,32,64)' => ['hsl(16,32,64)', 16, 32, 64, 1],
            'hsla(16,32,64,0.33)' => ['hsla(16,32,64,0.33)', 16, 32, 64, 0.33],
        ];
    }

    public function invalid_color_strings()
    {
        return [
            '(empty string)' => [''],
            '12' => ['12'],
            'hsl(16,32,64,0.33)' => ['hsl(16,32,64,0.33)'],
            'hsla(16,32,64)' => ['hsla(16,32,64)'],
            '#123456' => ['#123456'],
            'rgb(16,32,64)' => ['rgb(16,32,64)'],
        ];
    }

    public function invalid_color_values()
    {
        return [
            'saturation:negative' => [34, -1, 56, 1.0],
            'saturation:too-big' => [34, 101, 56, 1.0],

            'lightness:negative' => [34, 56, -1, 1.0],
            'lightness:too-big' => [34, 56, 101, 1.0],

            'alpha:negative' => [12, 34, 56, -0.1],
            'alpha:too-big' => [12, 34, 56, 1.1],
        ];
    }

    public function modify_red_channel()
    {
        return [
            'red:127' => ['hsl(12,34,56)', 127, 'hsl(41,9.6,45.4)'],
            'red:+10' => ['hsl(12,34,56)', '+10', 'hsl(11,40.3,58)'],
            'red:-10' => ['hsl(12,34,56)', '-10', 'hsl(14,28.3,54)'],
            'red:+10%' => ['hsl(12,34,56)', '+10%', 'hsl(10,45.8,59.5)'],
            'red:-10%' => ['hsl(12,34,56)', '-10%', 'hsl(16,24,52.5)'],

            'red:+300' => ['hsl(12,34,56)', '+300', 'hsl(6,100,70.5)'],
            'red:-300' => ['hsl(12,34,56)', '-300', 'hsl(172,100,23.5)'],
        ];
    }

    public function modify_green_channel()
    {
        return [
            'green:127' => ['hsl(12,34,56)', 127, 'hsl(18,34,56)'],
            'green:+50' => ['hsl(12,34,56)', '+50', 'hsl(51,34,56)'],
            'green:-50' => ['hsl(12,34,56)', '-50', 'hsl(-19,44.3,49.2)'],
            'green:+20%' => ['hsl(12,34,56)', '+20%', 'hsl(31,34,56)'],
            'green:-20%' => ['hsl(12,34,56)', '-20%', 'hsl(-6,36.5,54.3)'],

            'green:+300' => ['hsl(12,34,56)', '+300', 'hsl(90,100,70.5)'],
            'green:-300' => ['hsl(12,34,56)', '-300', 'hsl(-35,100,35.5)'],
        ];
    }

    public function modify_blue_channel()
    {
        return [
            'blue:127' => ['hsl(12,34,56)', 127, 'hsl(-7,29.2,59)'],
            'blue:+50' => ['hsl(12,34,56)', '+50', 'hsl(-34,29.2,59)'],
            'blue:-50' => ['hsl(12,34,56)', '-50', 'hsl(31,53.6,46.2)'],
            'blue:+20%' => ['hsl(12,34,56)', '+20%', 'hsl(-6,29.2,59)'],
            'blue:-20%' => ['hsl(12,34,56)', '-20%', 'hsl(22,39.6,51.9)'],

            'blue:+300' => ['hsl(12,34,56)', '+300', 'hsl(267,100,73.5)'],
            'blue:-300' => ['hsl(12,34,56)', '-300', 'hsl(40,100,35.5)'],
        ];
    }

    public function modify_alpha_channel()
    {
        return [
            'alpha:0.5' => ['hsl(12,34,56)', 0.5, 'hsla(12,34,56,0.5)'],
            'alpha:+0.1' => ['hsla(12,34,56,0.5)', '+0.1', 'hsla(12,34,56,0.6)'],
            'alpha:-0.1' => ['hsla(12,34,56,0.5)', '-0.1', 'hsla(12,34,56,0.4)'],
            'alpha:+10%' => ['hsla(12,34,56,0.5)', '+10%', 'hsla(12,34,56,0.55)'],
            'alpha:-10%' => ['hsla(12,34,56,0.5)', '-10%', 'hsla(12,34,56,0.45)'],

            'alpha:+2' => ['hsla(12,34,56,0.5)', '+2', 'hsl(12,34,56)'],
            'alpha:-2' => ['hsla(12,34,56,0.5)', '-2', 'hsla(12,34,56,0)'],
        ];
    }

    public function modify_hue()
    {
        return [
            'hue:127' => ['hsl(12,34,56)', 127, 'hsl(127,34,56)'],
            'hue:+50' => ['hsl(12,34,56)', '+50', 'hsl(62,34,56)'],
            'hue:-50' => ['hsl(12,34,56)', '-50', 'hsl(-38,34,56)'],
            'hue:+20%' => ['hsl(12,34,56)', '+20%', 'hsl(14,34,56)'],
            'hue:-20%' => ['hsl(12,34,56)', '-20%', 'hsl(10,34,56)'],

            'hue:+450' => ['hsl(12,34,56)', '+450', 'hsl(462,34,56)'],
            'hue:-450' => ['hsl(12,34,56)', '-450', 'hsl(-438,34,56)'],
        ];
    }

    public function modify_saturation()
    {
        return [
            'saturation:50' => ['hsl(12,34,56)', 50, 'hsl(12,50,56)'],
            'saturation:+50' => ['hsl(12,34,56)', '+50', 'hsl(12,84,56)'],
            'saturation:-25' => ['hsl(12,34,56)', '-25', 'hsl(12,9,56)'],
            'saturation:+20%' => ['hsl(12,34,56)', '+20%', 'hsl(12,40.8,56)'],
            'saturation:-20%' => ['hsl(12,34,56)', '-20%', 'hsl(12,27.2,56)'],

            'saturation:+100' => ['hsl(12,34,56)', '+100', 'hsl(12,100,56)'],
            'saturation:-100' => ['hsl(12,34,56)', '-100', 'hsl(12,0,56)'],
        ];
    }

    public function modify_lightness()
    {
        return [
            'lightness:50' => ['hsl(12,34,56)', 50, 'hsl(12,34,50)'],
            'lightness:+25' => ['hsl(12,34,56)', '+25', 'hsl(12,34,81)'],
            'lightness:-50' => ['hsl(12,34,56)', '-50', 'hsl(12,34,6)'],
            'lightness:+20%' => ['hsl(12,34,56)', '+20%', 'hsl(12,34,67.2)'],
            'lightness:-20%' => ['hsl(12,34,56)', '-20%', 'hsl(12,34,44.8)'],

            'lightness:+100' => ['hsl(12,34,56)', '+100', 'hsl(12,34,100)'],
            'lightness:-100' => ['hsl(12,34,56)', '-100', 'hsl(12,34,0)'],
        ];
    }
}
