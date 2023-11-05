<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\AspectMatching\ClassHierarchyOnlyInvokedOnce;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect as AspectAttribute;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;

#[AspectAttribute]
class Aspect
{
    #[After(
        class: 'Okapi\Aop\Tests\Functional\AspectMatching\ClassHierarchyOnlyInvokedOnce\Target\TargetClass*',
        method: '*',
    )]
    public function logMethodCalls(): void
    {
        static $count = 0;
        $count++;

        $stackTrace = StackTrace::getInstance();
        $stackTrace->addTrace("Method call $count");
    }
}
