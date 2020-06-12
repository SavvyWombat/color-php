<?php

declare(strict_types=1);

namespace SavvyWombat\Color;

abstract class AbstractColor
{
    protected $red;
    protected $green;
    protected $blue;
    protected $alpha;

    private static $acceptable = [
        '#([0-9a-f])([0-9a-f])([0-9a-f])' => Hex::class,
        '#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})' => Hex::class,
        '#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})' => Hex::class,
    ];

    final protected static function match(string $colorSpec, string $filter): ?array
    {
        $accepted = array_keys(array_filter(
            AbstractColor::$acceptable,
            function($value) use ($filter) {
                return $value === $filter;
            }
        ));

        $matches = [];
        while (empty($matches) && $accepted) {
            $pattern = array_pop($accepted);
            preg_match("/^ *{$pattern} *$/i", $colorSpec, $matches);
            var_dump($pattern, $matches);
        }
        
        return $matches;
    }

    public function red(): int
    {
        return $this->red;
    }

    public function green(): int
    {
        return $this->green;
    }

    public function blue(): int
    {
        return $this->blue;
    }

    public function alpha(): float
    {
        return round($this->alpha, 2);
    }
}
