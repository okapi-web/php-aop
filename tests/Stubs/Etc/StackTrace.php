<?php

namespace Okapi\Aop\Tests\Stubs\Etc;

use Okapi\Singleton\Singleton;

class StackTrace
{
    use Singleton;

    private array $stackTrace = [];

    public function addTrace(string $trace): void
    {
        $this->stackTrace[] = $trace;
    }

    public function getStackTrace(): array
    {
        return $this->stackTrace;
    }
}
