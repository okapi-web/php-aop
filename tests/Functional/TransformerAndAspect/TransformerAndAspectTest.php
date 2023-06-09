<?php

namespace Okapi\Aop\Tests\Functional\TransformerAndAspect;

use Okapi\Aop\Tests\Functional\TransformerAndAspect\Aspect\FixWrongReturnValueAspect;
use Okapi\Aop\Tests\Functional\TransformerAndAspect\Transformer\FixDeprecatedFunctionTransformer;
use Okapi\Aop\Tests\Stubs\Kernel\TransformerAndAspectKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class TransformerAndAspectTest extends TestCase
{
    /**
     * @see FixDeprecatedFunctionTransformer
     * @see FixWrongReturnValueAspect::fixWrongReturnValue()
     */
    public function testTransformerAndAspect(): void
    {
        Util::clearCache();
        TransformerAndAspectKernel::init();

        $class = new ClassesToIntercept\DeprecatedAndWrongClass();
        $this->assertTrue($class->checkIfFloat(1.0));
    }

    public function testCachedTransformerAndAspect(): void
    {
        TransformerAndAspectKernel::init();

        $class = new ClassesToIntercept\DeprecatedAndWrongClass();
        $this->assertTrue($class->checkIfFloat(42.0));
        $this->assertFalse($class->checkIfFloat("Hello World!"));
    }
}
