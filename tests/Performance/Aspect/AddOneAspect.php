<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Performance\Aspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Tests\Performance\Target\Numbers;

#[Aspect]
class AddOneAspect
{
    #[After(
        class: Numbers::class,
        method: 'get',
    )]
    public function addOne(AfterMethodInvocation $invocation): void
    {
        $result = $invocation->proceed();

        $invocation->setResult($result + 1);
    }
}
