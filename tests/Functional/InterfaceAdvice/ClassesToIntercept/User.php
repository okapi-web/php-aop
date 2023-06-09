<?php

namespace Okapi\Aop\Tests\Functional\InterfaceAdvice\ClassesToIntercept;

class User implements UserInterface
{
    public function getName(): string
    {
        return 'John Doe';
    }
}
