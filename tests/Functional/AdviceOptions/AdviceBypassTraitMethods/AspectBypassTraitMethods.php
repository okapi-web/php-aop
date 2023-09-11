<?php

namespace Okapi\Aop\Tests\Functional\AdviceOptions\AdviceBypassTraitMethods;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;

#[Aspect]
class AspectBypassTraitMethods
{
    #[After(
        class: 'Okapi\Aop\Tests\Functional\AdviceOptions\AdviceBypassTraitMethods\Target*',
        method: '*',
        bypassTraitMethods: true,
    )]
    public function validateContent()
    {
        $stackTrace = StackTrace::getInstance();
        $stackTrace->addTrace('AspectBypassTraitMethods');
    }
}
