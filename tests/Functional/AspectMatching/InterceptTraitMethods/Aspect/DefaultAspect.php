<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\AspectMatching\InterceptTraitMethods\Aspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;

#[Aspect]
class DefaultAspect
{
    #[After(
        class: 'Okapi\Aop\Tests\Functional\AspectMatching\InterceptTraitMethods\Target\Target*',
        method: '*',
    )]
    public function validateContent(): void
    {
        $stackTrace = StackTrace::getInstance();
        $stackTrace->addTrace('DefaultAspect');
    }
}
