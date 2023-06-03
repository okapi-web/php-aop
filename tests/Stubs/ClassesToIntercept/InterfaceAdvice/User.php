<?php

namespace Okapi\Aop\Tests\Stubs\ClassesToIntercept\InterfaceAdvice;

class User implements UserInterface
{
    public function getName(): string
    {
        return 'John Doe';
    }
}
