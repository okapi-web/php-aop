<?php

namespace Okapi\Aop\Tests\Functional\AspectMatching\JetBrainsAttribute;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AspectMatching\JetBrainsAttribute\Target\Car;
use Okapi\Aop\Tests\Stubs\Kernel\EmptyKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class JetBrainsAttributeTest extends TestCase
{
    use ClassLoaderMockTrait;

    public function testDeprecatedAttribute(): void
    {
        Util::clearCache();
        EmptyKernel::init();

        $this->assertAspectNotApplied(Car::class);
        $car = new Car();
        /** @noinspection PhpDeprecationInspection */
        $car->startCar();

        $this->expectOutputString('Car started');
    }
}
