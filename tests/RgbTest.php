<?php

declare(strict_types=1);

namespace SavvyWombat\Color\Test;

use PHPUnit\Framework\TestCase;
use SavvyWombat\Color\Exception;
use SavvyWombat\Color\Rgb;

class RgbTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider valid_color_strings
     */
    public function creates_an_rgb_color_from_a_string($colorSpec, $red, $green, $blue, $alpha): void
    {
        $rgb = Rgb::fromString($colorSpec);

        $this->assertInstanceOf(Rgb::class, $rgb);

        $this->assertEquals($red, $rgb->red);
        $this->assertEquals($green, $rgb->green);
        $this->assertEquals($blue, $rgb->blue);
        $this->assertEquals($alpha, round($rgb->alpha, 2));
    }

    /**
     * @test
     *
     * @dataProvider invalid_color_strings
     */
    public function throws_an_error_if_provided_an_invalid_color_string($colorSpec): void
    {
        $this->expectException(Exception::class);

        Rgb::fromString($colorSpec);
    }

    /**
     * @test
     *
     * @dataProvider invalid_color_values
     */
    public function throws_an_error_if_passed_invalid_color_values($red, $green, $blue, $alpha): void
    {
        $this->expectException(Exception::class);

        new Rgb($red, $green, $blue, $alpha);
    }

    /**
     * @test
     */
    public function creates_a_new_rgb_color_from_an_rgb_color(): void
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
    public function creates_a_new_rgb_color_from_an_rgb_color_with_alpha(): void
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
    public function castable_to_string(): void
    {
        $rgb = Rgb::fromString('rgb(32,64,128)');

        $this->assertEquals('rgb(32,64,128)', (string) $rgb);
    }

    /**
     * @test
     */
    public function castable_to_string_with_alpha_channel(): void
    {
        $rgb = Rgb::fromString('rgba(32,64,128,0.67)');

        $this->assertEquals('rgba(32,64,128,0.67)', (string) $rgb);
    }

    /**
     * @test
     *
     * @dataProvider modify_red_channel
     */
    public function can_modify_red_channel($initialColor, $newRedValue, $result): void
    {
        $rgb = Rgb::fromString($initialColor);
        $newRgb = $rgb->red($newRedValue);

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($rgb, $newRgb);
        $this->assertEquals($initialColor, (string) $rgb);
        $this->assertEquals($result, (string) $newRgb);
    }

    /**
     * @test
     *
     * @dataProvider modify_green_channel
     */
    public function can_modify_green_channel($initialColor, $newGreenValue, $result): void
    {
        $rgb = Rgb::fromString($initialColor);
        $newRgb = $rgb->green($newGreenValue);

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($rgb, $newRgb);
        $this->assertEquals($initialColor, (string) $rgb);
        $this->assertEquals($result, (string) $newRgb);
    }

    /**
     * @test
     *
     * @dataProvider modify_blue_channel
     */
    public function can_modify_blue_channel($initialColor, $newBlueValue, $result): void
    {
        $rgb = Rgb::fromString($initialColor);
        $newRgb = $rgb->blue($newBlueValue);

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($rgb, $newRgb);
        $this->assertEquals($initialColor, (string) $rgb);
        $this->assertEquals($result, (string) $newRgb);
    }

    /**
     * @test
     *
     * @dataProvider modify_alpha_channel
     */
    public function can_modify_alpha_channel($initialColor, $newAlphaValue, $result): void
    {
        $rgb = Rgb::fromString($initialColor);
        $newRgb = $rgb->alpha($newAlphaValue);

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($rgb, $newRgb);
        $this->assertEquals($initialColor, (string) $rgb);
        $this->assertEquals($result, (string) $newRgb);
    }

    /**
     * @test
     *
     * @dataProvider modify_hue
     */
    public function can_modify_hue($initialColor, $newHueValue, $result): void
    {
        $rgb = Rgb::fromString($initialColor);
        $newRgb = $rgb->hue($newHueValue);

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($rgb, $newRgb);
        $this->assertEquals($initialColor, (string) $rgb);
        $this->assertEquals($result, (string) $newRgb);
    }

    /**
     * @test
     *
     * @dataProvider modify_saturation
     */
    public function can_modify_saturation($initialColor, $newSaturationValue, $result): void
    {
        $rgb = Rgb::fromString($initialColor);
        $newRgb = $rgb->saturation($newSaturationValue);

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($rgb, $newRgb);
        $this->assertEquals($initialColor, (string) $rgb);
        $this->assertEquals($result, (string) $newRgb);
    }

    /**
     * @test
     *
     * @dataProvider modify_lightness
     */
    public function can_modify_lightness($initialColor, $newLightnessValue, $result): void
    {
        $rgb = Rgb::fromString($initialColor);
        $newRgb = $rgb->lightness($newLightnessValue);

        $this->assertInstanceOf(Rgb::class, $newRgb);
        $this->assertNotSame($rgb, $newRgb);
        $this->assertEquals($initialColor, (string) $rgb);
        $this->assertEquals($result, (string) $newRgb);
    }

    public function valid_color_strings(): array
    {
        return [
            'rgb(16,32,64)' => ['rgb(16,32,64)', 16, 32, 64, 1],
            'rgba(16,32,64,0.33)' => ['rgba(16,32,64,0.33)', 16, 32, 64, 0.33],
            'rgb(16, 32, 64)' => ['rgb(16,32,64)', 16, 32, 64, 1],
            'rgba(16, 32, 64, 0.33)' => ['rgba(16,32,64,0.33)', 16, 32, 64, 0.33],
        ];
    }

    public function invalid_color_strings(): array
    {
        return [
            '(empty string)' => [''],
            '12' => ['12'],
            'rgb(16,32,64,0.33)' => ['rgb(16,32,64,0.33)'],
            'rgba(16,32,64)' => ['rgba(16,32,64)'],
            '#123456' => ['#123456'],
        ];
    }

    public function invalid_color_values(): array
    {
        return [
            'red:negative' => [-1, 34, 56, 1.0],
            'red:too-big' => [256, 34, 56, 1.0],

            'green:negative' => [34, -1, 56, 1.0],
            'green:too-big' => [34, 256, 56, 1.0],

            'blue:negative' => [34, 56, -1, 1.0],
            'blue:too-big' => [34, 56, 256, 1.0],

            'alpha:negative' => [12, 34, 56, -0.1],
            'alpha:too-big' => [12, 34, 56, 1.1],
        ];
    }

    public function modify_red_channel(): array
    {
        return [
            'red:127' => ['rgb(12,34,56)', 127, 'rgb(127,34,56)'],
            'red:+10' => ['rgb(12,34,56)', '+10', 'rgb(22,34,56)'],
            'red:-10' => ['rgb(12,34,56)', '-10', 'rgb(2,34,56)'],
            'red:+10%' => ['rgb(12,34,56)', '+10%', 'rgb(13,34,56)'],
            'red:-10%' => ['rgb(12,34,56)', '-10%', 'rgb(11,34,56)'],

            'red:+300' => ['rgb(12,34,56)', '+300', 'rgb(255,34,56)'],
            'red:-300' => ['rgb(12,34,56)', '-300', 'rgb(0,34,56)'],

            'red:+3/4' => ['rgb(12,34,56)', '+3/4', 'rgb(194,34,56)'],
            'red:-4/5' => ['rgb(12,34,56)', '-4/5', 'rgb(2,34,56)'],
        ];
    }

    public function modify_green_channel(): array
    {
        return [
            'green:127' => ['rgb(12,34,56)', 127, 'rgb(12,127,56)'],
            'green:+10' => ['rgb(12,34,56)', '+10', 'rgb(12,44,56)'],
            'green:-10' => ['rgb(12,34,56)', '-10', 'rgb(12,24,56)'],
            'green:+10%' => ['rgb(12,34,56)', '+10%', 'rgb(12,37,56)'],
            'green:-10%' => ['rgb(12,34,56)', '-10%', 'rgb(12,31,56)'],

            'green:+300' => ['rgb(12,34,56)', '+300', 'rgb(12,255,56)'],
            'green:-300' => ['rgb(12,34,56)', '-300', 'rgb(12,0,56)'],

            'green:+1/2' => ['rgb(12,34,56)', '+1/2', 'rgb(12,145,56)'],
            'green:-1/10' => ['rgb(12,34,56)', '-1/10', 'rgb(12,31,56)'],
        ];
    }

    public function modify_blue_channel(): array
    {
        return [
            'blue:127' => ['rgb(12,34,56)', 127, 'rgb(12,34,127)'],
            'blue:+10' => ['rgb(12,34,56)', '+10', 'rgb(12,34,66)'],
            'blue:-10' => ['rgb(12,34,56)', '-10', 'rgb(12,34,46)'],
            'blue:+10%' => ['rgb(12,34,56)', '+10%', 'rgb(12,34,62)'],
            'blue:-10%' => ['rgb(12,34,56)', '-10%', 'rgb(12,34,50)'],

            'blue:+300' => ['rgb(12,34,56)', '+300', 'rgb(12,34,255)'],
            'blue:-300' => ['rgb(12,34,56)', '-300', 'rgb(12,34,0)'],

            'blue:+5/6' => ['rgb(12,34,56)', '+5/6', 'rgb(12,34,222)'],
            'blue:-6/7' => ['rgb(12,34,56)', '-6/7', 'rgb(12,34,8)'],
        ];
    }

    public function modify_alpha_channel(): array
    {
        return [
            'alpha:0.5' => ['rgb(12,34,56)', 0.5, 'rgba(12,34,56,0.5)'],
            'alpha:+0.1' => ['rgba(12,34,56,0.5)', '+0.1', 'rgba(12,34,56,0.6)'],
            'alpha:-0.1' => ['rgba(12,34,56,0.5)', '-0.1', 'rgba(12,34,56,0.4)'],
            'alpha:+10%' => ['rgba(12,34,56,0.5)', '+10%', 'rgba(12,34,56,0.55)'],
            'alpha:-10%' => ['rgba(12,34,56,0.5)', '-10%', 'rgba(12,34,56,0.45)'],

            'alpha:+2' => ['rgba(12,34,56,0.5)', '+2', 'rgb(12,34,56)'],
            'alpha:-2' => ['rgba(12,34,56,0.5)', '-2', 'rgba(12,34,56,0)'],

            'alpha:+1/2' => ['rgba(12,34,56,0.5)', '+1/2', 'rgba(12,34,56,0.75)'],
            'alpha:-2/3' => ['rgba(12,34,56,0.5)', '-2/3', 'rgba(12,34,56,0.17)'],
        ];
    }

    public function modify_hue(): array
    {
        return [
            'hue:180' => ['rgb(12,34,56)', 180, 'rgb(12,56,56)'],
            'hue:+45' => ['rgb(12,34,56)', '+45', 'rgb(23,12,56)'],
            'hue:-45' => ['rgb(12,34,56)', '-45', 'rgb(12,56,45)'],
            'hue:+10%' => ['rgb(12,34,56)', '+10%', 'rgb(12,19,56)'],
            'hue:-10%' => ['rgb(12,34,56)', '-10%', 'rgb(12,49,56)'],

            'hue:+450' => ['rgb(12,34,56)', '+450', 'rgb(56,12,56)'],
            'hue:-450' => ['rgb(12,34,56)', '-450', 'rgb(12,56,12)'],
        ];
    }

    public function modify_saturation(): array
    {
        return [
            'saturation:25' => ['rgb(12,34,56)', 25, 'rgb(26,34,43)'],
            'saturation:+25' => ['rgb(12,34,56)', '+25', 'rgb(4,34,65)'],
            'saturation:-25' => ['rgb(12,34,56)', '-25', 'rgb(21,34,48)'],
            'saturation:+10%' => ['rgb(12,34,56)', '+10%', 'rgb(10,34,58)'],
            'saturation:-10%' => ['rgb(12,34,56)', '-10%', 'rgb(14,34,54)'],

            'saturation:+100' => ['rgb(12,34,56)', '+100', 'rgb(0,34,68)'],
            'saturation:-100' => ['rgb(12,34,56)', '-100', 'rgb(34,34,34)'],
        ];
    }

    public function modify_lightness(): array
    {
        return [
            'lightness:25' => ['rgb(12,34,56)', 25, 'rgb(23,64,105)'],
            'lightness:+10' => ['rgb(12,34,56)', '+10', 'rgb(21,60,98)'],
            'lightness:-10' => ['rgb(12,34,56)', '-10', 'rgb(3,9,14)'],
            'lightness:+10%' => ['rgb(12,34,56)', '+10%', 'rgb(13,37,62)'],
            'lightness:-10%' => ['rgb(12,34,56)', '-10%', 'rgb(11,31,50)'],

            'lightness:+100' => ['rgb(12,34,56)', '+100', 'rgb(255,255,255)'],
            'lightness:-100' => ['rgb(12,34,56)', '-100', 'rgb(0,0,0)'],
        ];
    }
}
