<?php

declare(strict_types=1);

namespace SavvyWombat\Color\Test;

use PHPUnit\Framework\TestCase;
use SavvyWombat\Color\AbstractColor;
use SavvyWombat\Color\Rgb;

class AbstractColorTest extends TestCase
{
    /**
     * @test
     */
    public function creates_an_rgb_color_with_alpha()
    {
        $baseColor = new BaseColor(100, 101, 110, 0.45);

        $rgb = $baseColor->toRgb();

        $this->assertInstanceOf(Rgb::class, $rgb);
        $this->assertEquals(100, $rgb->red());
        $this->assertEquals(101, $rgb->green());
        $this->assertEquals(110, $rgb->blue());
        $this->assertEquals(0.45, $rgb->alpha());
    }
}

class BaseColor extends AbstractColor
{
    public function __construct($red, $green, $blue, $alpha)
    {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->alpha = $alpha;
    }
}
