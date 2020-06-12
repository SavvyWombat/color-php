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

        $this->assertEquals(18, $hex->red());
        $this->assertEquals(52, $hex->green());
        $this->assertEquals(86, $hex->blue());
        $this->assertEquals(1, $hex->alpha());
    }

    /**
     * @test
     */
    public function creates_a_hex_color_from_a_three_letter_hex_string()
    {
        $hex = Hex::fromString('#123');

        $this->assertInstanceOf(Hex::class, $hex);

        $this->assertEquals(17, $hex->red());
        $this->assertEquals(34, $hex->green());
        $this->assertEquals(51, $hex->blue());
        $this->assertEquals(1, $hex->alpha());
    }

    /**
     * @test
     */
    public function creates_a_hex_color_from_a_hex_string_with_alpha()
    {
        $hex = Hex::fromString('#12345678');

        $this->assertInstanceOf(Hex::class, $hex);

        $this->assertEquals(18, $hex->red());
        $this->assertEquals(52, $hex->green());
        $this->assertEquals(86, $hex->blue());
        $this->assertEquals(0.47, $hex->alpha());
    }

    /**
     * @test
     */
    public function creates_a_hex_color_from_an_rgb_color()
    {
        $rgb = Rgb::fromString('rgb(12,36,108)');
        $hex = Hex::fromRgb($rgb);

        $this->assertInstanceOf(Hex::class, $hex);

        $this->assertEquals(12, $hex->red());
        $this->assertEquals(36, $hex->green());
        $this->assertEquals(108, $hex->blue());
        $this->assertEquals(1, $hex->alpha());
    }

    /**
     * @test
     */
    public function creates_a_hex_color_from_an_rgb_color_with_alpha()
    {
        $rgb = Rgb::fromString('rgba(12,36,108,0.33)');
        $hex = Hex::fromRgb($rgb);

        $this->assertInstanceOf(Hex::class, $hex);

        $this->assertEquals(12, $hex->red());
        $this->assertEquals(36, $hex->green());
        $this->assertEquals(108, $hex->blue());
        $this->assertEquals(0.33, $hex->alpha());
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
}
