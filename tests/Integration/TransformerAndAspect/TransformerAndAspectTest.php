<?php

namespace Okapi\Aop\Tests\Integration\TransformerAndAspect;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Integration\TransformerAndAspect\Aspect\FixWrongReturnValueAspect;
use Okapi\Aop\Tests\Integration\TransformerAndAspect\Target\DeprecatedAndWrongClass;
use Okapi\Aop\Tests\Integration\TransformerAndAspect\Transformer\FixDeprecatedFunctionTransformer;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class TransformerAndAspectTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see FixDeprecatedFunctionTransformer
     * @see FixWrongReturnValueAspect::fixWrongReturnValue()
     */
    public function testTransformerAndAspect(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(DeprecatedAndWrongClass::class);
        $class = new DeprecatedAndWrongClass();
        $this->assertTrue($class->checkIfFloat(1.0));
    }

    public function testCachedTransformerAndAspect(): void
    {
        Kernel::init();

        $this->assertAspectLoadedFromCache(DeprecatedAndWrongClass::class);
        $class = new DeprecatedAndWrongClass();
        $this->assertTrue($class->checkIfFloat(42.0));
        $this->assertFalse($class->checkIfFloat("Hello World!"));
    }
}
