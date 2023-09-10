<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\ClassHierarchyOnlyInvokedOnce\Aspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;

#[Aspect]
class ClassHierarchyOnlyInvokedOnceAspect
{
    #[After(
        class: 'Okapi\Aop\Tests\Functional\ClassHierarchyOnlyInvokedOnce\Target\TargetClass*',
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
