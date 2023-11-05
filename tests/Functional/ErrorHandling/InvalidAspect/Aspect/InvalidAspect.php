<?php

namespace Okapi\Aop\Tests\Functional\ErrorHandling\InvalidAspect\Aspect;

use Okapi\Aop\Attributes\Before;

class InvalidAspect
{
    #[Before(
        class: 'Nice',
        method: 'test',
    )]
    public function test(): string
    {
        return 'I am invalid!';
    }
}
