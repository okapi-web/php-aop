<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\InterfaceAdvice\Target;

class User implements UserInterface
{
    public function getName(): string
    {
        return 'John Doe';
    }
}
