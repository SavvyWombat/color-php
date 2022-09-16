<?php

declare(strict_types=1);

namespace SavvyWombat\Color\Test;

use PHPUnit\Framework\TestCase;
use SavvyWombat\Color\Exception;
use SavvyWombat\Color\Hex;
use SavvyWombat\Color\Rgb;

class HexTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider valid_hex_strings
     */
    public function creates_a_hex_color_from_a_hex_string($colorSpec, $red, $green, $blue, $alpha): void
    {
        $hex = Hex::fromString($colorSpec);

        $this->assertInstanceOf(Hex::class, $hex);

        $this->assertEquals($red, $hex->red);
        $this->assertEquals($green, $hex->green);
        $this->assertEquals($blue, $hex->blue);
        $this->assertEquals($alpha, round($hex->alpha, 2));
    }

    /**
     * @test
     *
     * @dataProvider invalid_hex_strings
     */
    public function throws_error_if_color_spec_is_invalid($colorSpec): void
    {
        $this->expectException(Exception::class);

        Hex::fromString($colorSpec);
    }

    /**
     * @test
     *
     * @dataProvider valid_hex_names
     */
    public function creates_a_color_from_a_name($name, $value): void
    {
        $hex = Hex::fromString($name);

        $this->assertInstanceOf(Hex::class, $hex);
        $this->assertEquals($value, (string) $hex);
    }

    /**
     * @test
     *
     * @dataProvider invalid_channels
     */
    public function throws_error_if_channel_is_not_valid($red, $green, $blue, $alpha): void
    {
        $this->expectException(Exception::class);

        new Hex($red, $green, $blue, $alpha);
    }

    /**
     * @test
     */
    public function creates_a_hex_color_from_an_rgb_color(): void
    {
        $rgb = Rgb::fromString('rgb(12,36,108)');
        $hex = Hex::fromRgb($rgb);

        $this->assertInstanceOf(Hex::class, $hex);

        $this->assertEquals(12, $hex->red);
        $this->assertEquals(36, $hex->green);
        $this->assertEquals(108, $hex->blue);
        $this->assertEquals(1, $hex->alpha);
    }

    /**
     * @test
     */
    public function creates_a_hex_color_from_an_rgb_color_with_alpha(): void
    {
        $rgb = Rgb::fromString('rgba(12,36,108,0.33)');
        $hex = Hex::fromRgb($rgb);

        $this->assertInstanceOf(Hex::class, $hex);

        $this->assertEquals(12, $hex->red);
        $this->assertEquals(36, $hex->green);
        $this->assertEquals(108, $hex->blue);
        $this->assertEquals(0.33, round($hex->alpha, 2));
    }

    /**
     * @test
     */
    public function castable_to_string(): void
    {
        $hex = Hex::fromString('#ABCDEF');

        $this->assertEquals('#abcdef', (string) $hex);
    }

    /**
     * @test
     */
    public function castable_to_string_with_alpha_channel(): void
    {
        $hex = Hex::fromString('#abcdef12');

        $this->assertEquals('#abcdef12', (string) $hex);
    }

    /**
     * @test
     */
    public function can_set_alpha_to_a_new_value(): void
    {
        $hex = Hex::fromString('#123456');
        $newHex = $hex->alpha('0.5');

        $this->assertInstanceOf(Hex::class, $newHex);
        $this->assertNotSame($hex, $newHex);
        $this->assertEquals('#12345680', (string) $newHex);
    }

    /**
     * @test
     */
    public function can_increase_alpha(): void
    {
        $hex = Hex::fromString('#12345610');
        $newHex = $hex->alpha('+0.5');

        $this->assertInstanceOf(Hex::class, $newHex);
        $this->assertNotSame($hex, $newHex);
        $this->assertEquals('#12345690', (string) $newHex);
    }

    /**
     * @test
     */
    public function can_decrease_alpha(): void
    {
        $hex = Hex::fromString('#123456ff');
        $newHex = $hex->alpha('-0.5');

        $this->assertInstanceOf(Hex::class, $newHex);
        $this->assertNotSame($hex, $newHex);
        $this->assertEquals('#12345680', (string) $newHex);
    }

    /**
     * @test
     */
    public function can_increase_alpha_by_a_relative_amount(): void
    {
        $hex = Hex::fromString('#12345610');
        $newHex = $hex->alpha('+20%');

        $this->assertInstanceOf(Hex::class, $newHex);
        $this->assertNotSame($hex, $newHex);
        $this->assertEquals('#12345613', (string) $newHex);
    }

    /**
     * @test
     */
    public function can_decrease_alpha_by_a_relative_amount(): void
    {
        $hex = Hex::fromString('#12345650');
        $newHex = $hex->alpha('-20%');

        $this->assertInstanceOf(Hex::class, $newHex);
        $this->assertNotSame($hex, $newHex);
        $this->assertEquals('#12345640', (string) $newHex);
    }

    public function valid_hex_strings()
    {
        return [
            '#123' => ['#123', 17, 34, 51, 1],
            '#1234' => ['#1234', 17, 34, 51, 0.27],
            '#123456' => ['#123456', 18, 52, 86, 1],
            '#12345678' => ['#12345678', 18, 52, 86, 0.47],
        ];
    }

    public function invalid_hex_strings()
    {
        return [
            '(empty string)' => [''],
            '123' => ['123'],
            '#12' => ['#12'],
            'rgb(12,34,56)' => ['rgb(12,34,56)'],
        ];
    }

    public function valid_hex_names()
    {
        return [
            'black' => ['black', '#000000'],
            'papayawhip' => ['papayawhip', '#ffefd5'],
            'wheat' => ['wheat', '#f5deb3'],
        ];
    }

    public function invalid_channels()
    {
        return [
            'red:' => ['', '34', '56', '78'],
            'red:g0' => ['g0', '34', '56', '78'],
            'red:123' => ['123', '34', '56', '78'],

            'green:' => ['12', '', '56', '78'],
            'green:g0' => ['12', 'g0', '56', '78'],
            'green:123' => ['12', '123', '56', '78'],

            'blue:' => ['12', '34', '', '78'],
            'blue:g0' => ['12', '34', 'g0', '78'],
            'blue:123' => ['12', '34', '123', '78'],

            'alpha:' => ['12', '34', '56', ''],
            'alpha:g0' => ['12', '34', '56', 'g0'],
            'alpha:123' => ['12', '34', '56', '123'],
        ];
    }
}
