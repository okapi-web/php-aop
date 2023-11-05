<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Integration\TransformerAndAspectDependencyInjectionHandler;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect as AspectAttribute;
use Okapi\Aop\Invocation\AfterMethodInvocation;

#[AspectAttribute]
class Aspect
{
    #[After(
        class: Target::class,
        method: 'answer',
    )]
    public function higherAnswer(AfterMethodInvocation $methodInvocation): int|float
    {
        $result = $methodInvocation->proceed();

        return $result + 378;
    }
}
