<?php

namespace Okapi\Aop\Tests\Performance\Target;

class Numbers
{
    private int $number = 0;

    public function get(): int
    {
        return $this->number;
    }

    public function add(int $number): void
    {
        $this->number += $number;
    }

    public function set(int $number): void
    {
        $this->number = $number;
    }
}
