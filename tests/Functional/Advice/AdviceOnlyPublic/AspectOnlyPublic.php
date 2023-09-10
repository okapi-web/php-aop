<?php

namespace Okapi\Aop\Tests\Functional\Advice\AdviceOnlyPublic;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Tests\Functional\Advice\AdviceOrder\ClassesToIntercept\ArticleManager;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;

#[Aspect]
class AspectOnlyPublic
{
    #[After(
        class: TargetClass::class,
        method: '*',
        onlyPublic: true,
    )]
    public function aspectOnlyPublic_validateContent()
    {
        $stackTrace = StackTrace::getInstance();
        $stackTrace->addTrace('AspectOnlyPublic');
    }
}
