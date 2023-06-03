<?php

namespace Okapi\Aop\Tests\Stubs\Etc;

use Okapi\Singleton\Singleton;

class Logger
{
    use Singleton;

    private array $log = [];

    public function log(string $message): void
    {
        $this->log[] = $message;
    }

    public function getLogs(): array
    {
        return $this->log;
    }
}
