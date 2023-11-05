<?php

namespace Okapi\Aop\Tests\Functional\Kernel\CustomDependencyInjectionHandler;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect as AspectAttribute;

#[AspectAttribute]
class Aspect
{
    #[After(
        class: Target::class,
        method: 'answer',
    )]
    public function higherAnswer(): int
    {
        return 420;
    }
}
