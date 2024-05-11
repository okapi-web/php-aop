<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\Include\Target;

class SecureDatabaseService
{
    private ?array $data = null;

    public function load(): self
    {
        if ($this->data === null) {
            $this->data = require dirname(__DIR__, 3) . '/AdviceBehavior/Include/Database/data.php';
        }

        return $this;
    }

    public function getData(): array
    {
        if ($this->data === null) {
            $this->load();
        }

        return $this->data;
    }
}
