<?php

declare(strict_types=1);

namespace SavvyWombat\Color\Test;

use PHPUnit\Framework\TestCase;
use SavvyWombat\Color\Exception;
use SavvyWombat\Color\Hsl;
use SavvyWombat\Color\Rgb;

class HslTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider valid_color_strings
     */
    public function creates_an_hsl_color_from_a_string($colorSpec, $hue, $saturation, $lightness, $alpha): void
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
     *
     * @dataProvider invalid_color_strings
     */
    public function throws_an_error_if_provided_an_invalid_color_string($colorSpec): void
    {
        $this->expectException(Exception::class);

        Hsl::fromString($colorSpec);
    }

    /**
     * @test
     *
     * @dataProvider invalid_color_values
     */
    public function throws_an_error_if_passed_invalid_color_values($hue, $saturation, $lightness, $alpha): void
    {
        $this->expectException(Exception::class);

        new Hsl($hue, $saturation, $lightness, $alpha);
    }

    /**
     * @test
     *
     * @dataProvider rgb_to_hsl
     */
    public function creates_an_hsl_color_from_an_rgb_color($rgbString, $hslString): void
    {
        $rgb = Rgb::fromString($rgbString);
        $hsl = Hsl::fromRgb($rgb);

        $this->assertInstanceOf(Hsl::class, $hsl);

        $this->assertEquals($hslString, (string) $hsl);
    }

    /**
     * @test
     */
    public function creates_an_hsl_color_from_an_rgb_color_with_alpha(): void
    {
        $rgb = Rgb::fromString('rgba(48,96,192,0.35)');
        $hsl = Hsl::fromRgb($rgb);

        $this->assertInstanceOf(Hsl::class, $hsl);

        $this->assertEquals(48.00, round($hsl->red, 2));
        $this->assertEquals(96.00, round($hsl->green, 2));
        $this->assertEquals(192.00, round($hsl->blue, 2));
        $this->assertEquals(0.35, round($hsl->alpha, 2));
    }

    /**
     * @test
     */
    public function castable_to_string(): void
    {
        $hsl = Hsl::fromString('hsl(16,32%,64%)');

        $this->assertEquals('hsl(16,32%,64%)', (string) $hsl);
    }

    /**
     * @test
     */
    public function castable_to_string_with_alpha_channel(): void
    {
        $hsl = Hsl::fromString('hsla(16,32%,64%,0.5)');

        $this->assertEquals('hsla(16,32%,64%,0.5)', (string) $hsl);
    }

    /**
     * @test
     *
     * @dataProvider modify_red_channel
     */
    public function can_modify_red_channel($initialColor, $newRedValue, $result): void
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
     *
     * @dataProvider modify_green_channel
     */
    public function can_modify_green_channel($initialColor, $newGreenValue, $result): void
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
     *
     * @dataProvider modify_blue_channel
     */
    public function can_modify_blue_channel($initialColor, $newBlueValue, $result): void
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
     *
     * @dataProvider modify_alpha_channel
     */
    public function can_modify_alpha_channel($initialColor, $newAlphaValue, $result): void
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
     *
     * @dataProvider modify_hue
     */
    public function can_modify_hue($initialColor, $newHueValue, $result): void
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
     *
     * @dataProvider modify_saturation
     */
    public function can_modify_saturation($initialColor, $newSaturationValue, $result): void
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
     *
     * @dataProvider modify_lightness
     */
    public function can_modify_lightness($initialColor, $newLightnessValue, $result): void
    {
        $hsl = Hsl::fromString($initialColor);
        $newHsl = $hsl->lightness($newLightnessValue);

        $this->assertInstanceOf(Hsl::class, $newHsl);
        $this->assertNotSame($hsl, $newHsl);
        $this->assertEquals($initialColor, (string) $hsl);
        $this->assertEquals($result, (string) $newHsl);
    }

    /**
     * @test
     *
     * @dataProvider hsl_to_rgb
     */
    public function converts_to_rgb($hslString, $rgbString): void
    {
        $hsl = Hsl::fromString($hslString);
        $rgb = $hsl->toRgb();

        $this->assertInstanceOf(Rgb::class, $rgb);
        $this->assertEquals($rgbString, (string) $rgb);
    }

    /**
     * @test
     *
     * @dataProvider invert_colors
     */
    public function inverts_colors($hslString, $invertedHueString): void
    {
        $hsl = Hsl::fromString($hslString);

        $invertedHsl = $hsl->invert();

        $this->assertInstanceOf(Hsl::class, $invertedHsl);
        $this->assertNotSame($hsl, $invertedHsl);
        $this->assertEquals($invertedHueString, (string) $invertedHsl);
    }

    public function valid_color_strings(): array
    {
        return [
            'hsl(16,32%,64%)' => ['hsl(16,32%,64%)', 16, 32, 64, 1],
            'hsl(16, 32%, 64%)' => ['hsl(16, 32%, 64%)', 16, 32, 64, 1],
            'hsl(16,32%,64%,0.33)' => ['hsl(16,32%,64%,0.33)', 16, 32, 64, 0.33],
            'hsl(16.12,32.23%,64.34%)' => ['hsl(16.12,32.23%,64.34%)', 16.12, 32.23, 64.34, 1],
            'hsl(16.00,32.00%,64.00%)' => ['hsl(16.00,32.00%,64.00%)', 16, 32, 64, 1],

            'hsla(16,32%,64%,0.33)' => ['hsla(16,32%,64%,0.33)', 16, 32, 64, 0.33],
            'hsla(16, 32%, 64%, 0.33)' => ['hsla(16, 32%, 64%, 0.33)', 16, 32, 64, 0.33],
            'hsla(16.12,32.23%,64.34%,0.33)' => ['hsla(16.12,32.23%,64.34%,0.33)', 16.12, 32.23, 64.34, 0.33],
            'hsla(16.00,32.00%,64.00%,0.33)' => ['hsla(16.00,32.00%,64.00%,0.33)', 16, 32, 64, 0.33],
        ];
    }

    public function invalid_color_strings(): array
    {
        return [
            '(empty string)' => [''],
            '12' => ['12'],
            'hsla(16,32%,64%)' => ['hsla(16,32%,64%)'],
            '#123456' => ['#123456'],
            'rgb(16,32%,64%)' => ['rgb(16,32%,64%)'],
        ];
    }

    public function invalid_color_values(): array
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

    public function modify_red_channel(): array
    {
        return [
            'red:127' => ['hsl(12,34%,56%)', 127, 'hsl(41,9.6%,45.4%)'],
            'red:+10' => ['hsl(12,34%,56%)', '+10', 'hsl(11,40.3%,58%)'],
            'red:-10' => ['hsl(12,34%,56%)', '-10', 'hsl(14,28.3%,54%)'],
            'red:+10%' => ['hsl(12,34%,56%)', '+10%', 'hsl(10,45.8%,59.5%)'],
            'red:-10%' => ['hsl(12,34%,56%)', '-10%', 'hsl(16,24%,52.5%)'],

            'red:+300' => ['hsl(12,34%,56%)', '+300', 'hsl(6,100%,70.5%)'],
            'red:-300' => ['hsl(12,34%,56%)', '-300', 'hsl(172,100%,23.5%)'],
        ];
    }

    public function modify_green_channel(): array
    {
        return [
            'green:127' => ['hsl(12,34%,56%)', 127, 'hsl(18,34%,56%)'],
            'green:+50' => ['hsl(12,34%,56%)', '+50', 'hsl(51,34%,56%)'],
            'green:-50' => ['hsl(12,34%,56%)', '-50', 'hsl(-19,44.3%,49.2%)'],
            'green:+20%' => ['hsl(12,34%,56%)', '+20%', 'hsl(31,34%,56%)'],
            'green:-20%' => ['hsl(12,34%,56%)', '-20%', 'hsl(-6,36.5%,54.3%)'],

            'green:+300' => ['hsl(12,34%,56%)', '+300', 'hsl(90,100%,70.5%)'],
            'green:-300' => ['hsl(12,34%,56%)', '-300', 'hsl(-35,100%,35.5%)'],
        ];
    }

    public function modify_blue_channel(): array
    {
        return [
            'blue:127' => ['hsl(12,34%,56%)', 127, 'hsl(-7,29.2%,59%)'],
            'blue:+50' => ['hsl(12,34%,56%)', '+50', 'hsl(-34,29.2%,59%)'],
            'blue:-50' => ['hsl(12,34%,56%)', '-50', 'hsl(31,53.6%,46.2%)'],
            'blue:+20%' => ['hsl(12,34%,56%)', '+20%', 'hsl(-6,29.2%,59%)'],
            'blue:-20%' => ['hsl(12,34%,56%)', '-20%', 'hsl(22,39.6%,51.9%)'],

            'blue:+300' => ['hsl(12,34%,56%)', '+300', 'hsl(267,100%,73.5%)'],
            'blue:-300' => ['hsl(12,34%,56%)', '-300', 'hsl(40,100%,35.5%)'],
        ];
    }

    public function modify_alpha_channel(): array
    {
        return [
            'alpha:0.5' => ['hsl(12,34%,56%)', 0.5, 'hsla(12,34%,56%,0.5)'],
            'alpha:+0.1' => ['hsla(12,34%,56%,0.5)', '+0.1', 'hsla(12,34%,56%,0.6)'],
            'alpha:-0.1' => ['hsla(12,34%,56%,0.5)', '-0.1', 'hsla(12,34%,56%,0.4)'],
            'alpha:+10%' => ['hsla(12,34%,56%,0.5)', '+10%', 'hsla(12,34%,56%,0.55)'],
            'alpha:-10%' => ['hsla(12,34%,56%,0.5)', '-10%', 'hsla(12,34%,56%,0.45)'],

            'alpha:+2' => ['hsla(12,34%,56%,0.5)', '+2', 'hsl(12,34%,56%)'],
            'alpha:-2' => ['hsla(12,34%,56%,0.5)', '-2', 'hsla(12,34%,56%,0)'],

            'alpha:+1/2' => ['hsla(12,34%,56%,0.5)', '+1/2', 'hsla(12,34%,56%,0.75)'],
            'alpha:-3/4' => ['hsla(12,34%,56%,0.5)', '-3/4', 'hsla(12,34%,56%,0.13)'],
        ];
    }

    public function modify_hue(): array
    {
        return [
            'hue:127' => ['hsl(12,34%,56%)', 127, 'hsl(127,34%,56%)'],
            'hue:+50' => ['hsl(12,34%,56%)', '+50', 'hsl(62,34%,56%)'],
            'hue:-50' => ['hsl(12,34%,56%)', '-50', 'hsl(-38,34%,56%)'],
            'hue:+20%' => ['hsl(12,34%,56%)', '+20%', 'hsl(14,34%,56%)'],
            'hue:-20%' => ['hsl(12,34%,56%)', '-20%', 'hsl(10,34%,56%)'],

            'hue:+450' => ['hsl(12,34%,56%)', '+450', 'hsl(462,34%,56%)'],
            'hue:-450' => ['hsl(12,34%,56%)', '-450', 'hsl(-438,34%,56%)'],
        ];
    }

    public function modify_saturation(): array
    {
        return [
            'saturation:50' => ['hsl(12,34%,56%)', 50, 'hsl(12,50%,56%)'],
            'saturation:+50' => ['hsl(12,34%,56%)', '+50', 'hsl(12,84%,56%)'],
            'saturation:-25' => ['hsl(12,34%,56%)', '-25', 'hsl(12,9%,56%)'],
            'saturation:+20%' => ['hsl(12,34%,56%)', '+20%', 'hsl(12,40.8%,56%)'],
            'saturation:-20%' => ['hsl(12,34%,56%)', '-20%', 'hsl(12,27.2%,56%)'],

            'saturation:+100' => ['hsl(12,34%,56%)', '+100', 'hsl(12,100%,56%)'],
            'saturation:-100' => ['hsl(12,34%,56%)', '-100', 'hsl(12,0%,56%)'],

            'saturation:+2/3' => ['hsla(12,34%,56%,0.5)', '+2/3', 'hsla(12,78%,56%,0.5)'],
            'saturation:-1/2' => ['hsla(12,34%,56%,0.5)', '-1/2', 'hsla(12,17%,56%,0.5)'],
        ];
    }

    public function modify_lightness(): array
    {
        return [
            'lightness:50' => ['hsl(12,34%,56%)', 50, 'hsl(12,34%,50%)'],
            'lightness:+25' => ['hsl(12,34%,56%)', '+25', 'hsl(12,34%,81%)'],
            'lightness:-50' => ['hsl(12,34%,56%)', '-50', 'hsl(12,34%,6%)'],
            'lightness:+20%' => ['hsl(12,34%,56%)', '+20%', 'hsl(12,34%,67.2%)'],
            'lightness:-20%' => ['hsl(12,34%,56%)', '-20%', 'hsl(12,34%,44.8%)'],

            'lightness:+100' => ['hsl(12,34%,56%)', '+100', 'hsl(12,34%,100%)'],
            'lightness:-100' => ['hsl(12,34%,56%)', '-100', 'hsl(12,34%,0%)'],

            'lightness:+2/5' => ['hsla(12,34%,56%,0.5)', '+2/5', 'hsla(12,34%,73.6%,0.5)'],
            'lightness:-1/3' => ['hsla(12,34%,56%,0.5)', '-1/3', 'hsla(12,34%,37.3%,0.5)'],
        ];
    }

    public function hsl_to_rgb(): array
    {
        return [
            'hsl(0,25%,75%)' => ['hsl(0,25%,75%)', 'rgb(207,175,175)'],
            'hsl(360,25%,75%)' => ['hsl(360,25%,75%)', 'rgb(207,175,175)'],
            'hsl(-360,25%,75%)' => ['hsl(-360,25%,75%)', 'rgb(207,175,175)'],
            'hsl(45,75%,25%)' => ['hsl(45,75%,25%)', 'rgb(112,88,16)'],
            'hsl(405,75%,25%)' => ['hsl(405,75%,25%)', 'rgb(112,88,16)'],
            'hsl(-315,75%,25%)' => ['hsl(-315,75%,25%)', 'rgb(112,88,16)'],
        ];
    }

    public function rgb_to_hsl(): array
    {
        return [
            'rgb(48,96,192)' => ['rgb(48,96,192)', 'hsl(220,60%,47.1%)'],
            'rgb(255,255,255)' => ['rgb(255,255,255)', 'hsl(0,0%,100%)'],
            'rgb(0,0,0)' => ['rgb(0,0,0)', 'hsl(0,0%,0%)'],
        ];
    }

    public function invert_colors(): array
    {
        return [
            'hsl(0,25%,75%)' => ['hsl(0,25%,75%)', 'hsl(180,25%,75%)'],
            'hsl(180,25%,75%)' => ['hsl(180,25%,75%)', 'hsl(0,25%,75%)'],
            'hsl(45,25%,75%)' => ['hsl(45,25%,75%)', 'hsl(225,25%,75%)'],
            'hsl(200,25%,75%)' => ['hsl(200,25%,75%)', 'hsl(20,25%,75%)'],
        ];
    }
}
