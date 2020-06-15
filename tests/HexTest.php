<?php

declare(strict_types=1);

namespace SavvyWombat\Color\Test;

use PHPUnit\Framework\TestCase;
use SavvyWombat\Color\Hex;
use SavvyWombat\Color\Rgb;

class HexTest extends TestCase
{
    /**
     * @test
     */
    public function creates_a_hex_color_from_a_hex_string()
    {
        $hex = Hex::fromString('#123456');

        $this->assertInstanceOf(Hex::class, $hex);

        $this->assertEquals(18, $hex->red);
        $this->assertEquals(52, $hex->green);
        $this->assertEquals(86, $hex->blue);
        $this->assertEquals(1, $hex->alpha);
    }

    /**
     * @test
     */
    public function creates_a_hex_color_from_a_three_letter_hex_string()
    {
        $hex = Hex::fromString('#123');

        $this->assertInstanceOf(Hex::class, $hex);

        $this->assertEquals(17, $hex->red);
        $this->assertEquals(34, $hex->green);
        $this->assertEquals(51, $hex->blue);
        $this->assertEquals(1, $hex->alpha);
    }

    /**
     * @test
     */
    public function creates_a_hex_color_from_a_hex_string_with_alpha()
    {
        $hex = Hex::fromString('#12345678');

        $this->assertInstanceOf(Hex::class, $hex);

        $this->assertEquals(18, $hex->red);
        $this->assertEquals(52, $hex->green);
        $this->assertEquals(86, $hex->blue);
        $this->assertEquals(0.47, round($hex->alpha, 2));
    }

    /**
     * @test
     */
    public function creates_a_hex_color_from_an_rgb_color()
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
    public function creates_a_hex_color_from_an_rgb_color_with_alpha()
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
    public function castable_to_string()
    {
        $hex = Hex::fromString('#ABCDEF');

        $this->assertEquals('#abcdef', (string) $hex);
    }

    /**
     * @test
     */
    public function castable_to_string_with_alpha_channel()
    {
        $hex = Hex::fromString('#ABCDEF12');

        $this->assertEquals('#abcdef12', (string) $hex);
    }

    /**
     * @test
     */
    public function can_set_alpha_to_a_new_value()
    {
        $hex = Hex::fromString('#123456');
        $newHex = $hex->alpha('0.5');

        $this->assertInstanceOf(Hex::class, $newHex);
        $this->assertNotSame($hex, $newHex);
        $this->assertEquals('#1234567f', (string) $newHex);
    }

    /**
     * @test
     */
    public function can_increase_alpha()
    {
        $hex = Hex::fromString('#12345610');
        $newHex = $hex->alpha('+0.5');

        $this->assertInstanceOf(Hex::class, $newHex);
        $this->assertNotSame($hex, $newHex);
        $this->assertEquals('#1234568f', (string) $newHex);
    }

    /**
     * @test
     */
    public function can_decrease_alpha()
    {
        $hex = Hex::fromString('#123456ff');
        $newHex = $hex->alpha('-0.5');

        $this->assertInstanceOf(Hex::class, $newHex);
        $this->assertNotSame($hex, $newHex);
        $this->assertEquals('#1234567f', (string) $newHex);
    }

    /**
     * @test
     */
    public function can_increase_alpha_by_a_relative_amount()
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
    public function can_decrease_alpha_by_a_relative_amount()
    {
        $hex = Hex::fromString('#12345650');
        $newHex = $hex->alpha('-20%');

        $this->assertInstanceOf(Hex::class, $newHex);
        $this->assertNotSame($hex, $newHex);
        $this->assertEquals('#12345640', (string) $newHex);
    }
}
