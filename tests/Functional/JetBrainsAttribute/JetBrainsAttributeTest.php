<?php

namespace Okapi\Aop\Tests\Functional\JetBrainsAttribute;

use Okapi\Aop\Tests\Functional\JetBrainsAttribute\ClassesToIntercept\Car;
use Okapi\Aop\Tests\Stubs\Kernel\EmptyKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class JetBrainsAttributeTest extends TestCase
{
    public function testDeprecatedAttribute(): void
    {
        Util::clearCache();
        EmptyKernel::init();

        $car = new Car();
        /** @noinspection PhpDeprecationInspection */
        $car->startCar();

        $this->expectOutputString('Car started');
    }
}
