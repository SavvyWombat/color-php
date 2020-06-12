<?php

declare(strict_types=1);

namespace SavvyWombat\Color\Test;

use PHPUnit\Framework\TestCase;
use SavvyWombat\Color\Hex;

class HexTest extends TestCase
{
    /**
     * @test
     */
    public function creates_a_hex_color_from_a_hex_string()
    {
        $hex = Hex::fromString("#123456");

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
        $hex = Hex::fromString("#123");

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
        $hex = Hex::fromString("#12345678");

        $this->assertInstanceOf(Hex::class, $hex);

        $this->assertEquals(18, $hex->red());
        $this->assertEquals(52, $hex->green());
        $this->assertEquals(86, $hex->blue());
        $this->assertEquals(0.47, $hex->alpha());
    }
}
