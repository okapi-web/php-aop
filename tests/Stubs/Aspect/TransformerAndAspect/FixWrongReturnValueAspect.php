<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Stubs\Aspect\TransformerAndAspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\TransformerAndAspect\DeprecatedAndWrongClass;

#[Aspect]
class FixWrongReturnValueAspect
{
    #[After(
        DeprecatedAndWrongClass::class,
        'checkIfFloat',
    )]
    public function fixWrongReturnValue(AfterMethodInvocation $invocation): void
    {
        $result = $invocation->proceed();
        $invocation->setResult(!$result);
    }
}
