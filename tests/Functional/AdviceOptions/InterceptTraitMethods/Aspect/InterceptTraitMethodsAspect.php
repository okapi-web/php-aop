<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\AdviceOptions\InterceptTraitMethods\Aspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;

#[Aspect]
class InterceptTraitMethodsAspect
{
    #[After(
        class: 'Okapi\Aop\Tests\Functional\AdviceOptions\InterceptTraitMethods\Target\Target*',
        method: '*',
        interceptTraitMethods: false,
    )]
    public function validateContent(): void
    {
        $stackTrace = StackTrace::getInstance();
        $stackTrace->addTrace('InterceptTraitMethodsAspect');
    }
}
