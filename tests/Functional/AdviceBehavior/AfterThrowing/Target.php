<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\AfterThrowing;

use Throwable;

class Target
{
    public function run(string $mode, ?Throwable $exception = null, string $label = 'original', int ...$values): string
    {
        State::$events[] = 'target';
        if ($exception !== null) {
            throw $exception;
        }
        return $label . array_sum($values);
    }

    public static function failStatic(Throwable $exception): void
    {
        throw $exception;
    }

    public function failNever(Throwable $exception): never
    {
        throw $exception;
    }

    public function unmatched(Throwable $exception): void
    {
        throw $exception;
    }
}
