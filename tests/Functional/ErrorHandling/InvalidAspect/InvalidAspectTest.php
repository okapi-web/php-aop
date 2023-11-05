<?php

namespace Okapi\Aop\Tests\Functional\ErrorHandling\InvalidAspect;

use Okapi\Aop\Core\Exception\Aspect\AspectNotFoundException;
use Okapi\Aop\Core\Exception\Aspect\InvalidAspectClassNameException;
use Okapi\Aop\Core\Exception\Aspect\MissingAspectAttributeException;
use Okapi\Aop\Tests\Functional\ErrorHandling\InvalidAspect\Kernel\InvalidAspectClassKernel;
use Okapi\Aop\Tests\Functional\ErrorHandling\InvalidAspect\Kernel\InvalidAspectClassNameKernel;
use Okapi\Aop\Tests\Functional\ErrorHandling\InvalidAspect\Kernel\InvalidAspectsTypeKernel;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class InvalidAspectTest extends TestCase
{
    /**
     * @see InvalidAspectClassNameKernel
     */
    public function testInvalidAspectClassName(): void
    {
        $this->expectException(AspectNotFoundException::class);

        InvalidAspectClassNameKernel::init();
    }

    /**
     * @see InvalidAspectsTypeKernel
     */
    public function testInvalidAspectType(): void
    {
        $this->expectException(InvalidAspectClassNameException::class);

        InvalidAspectsTypeKernel::init();
    }

    /**
     * @see InvalidAspectClassKernel
     */
    public function testInvalidAspectClass(): void
    {
        $this->expectException(MissingAspectAttributeException::class);

        InvalidAspectClassKernel::init();
    }
}
