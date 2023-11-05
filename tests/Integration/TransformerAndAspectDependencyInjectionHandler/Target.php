<?php

namespace Okapi\Aop\Tests\Integration\TransformerAndAspectDependencyInjectionHandler;

class Target
{
    public function answer(): int|float
    {
        return 42;
    }
}
