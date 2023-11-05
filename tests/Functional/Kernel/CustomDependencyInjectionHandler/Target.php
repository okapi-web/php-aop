<?php

namespace Okapi\Aop\Tests\Functional\Kernel\CustomDependencyInjectionHandler;

class Target
{
    public function answer(): int
    {
        return 42;
    }
}
