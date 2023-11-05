<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ModifyArgument;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ModifyArgument\Aspect\NumberHelperAspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ModifyArgument\Target\NumberHelper;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class ModifyArgumentTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see NumberHelperAspect::removeNegativeNumbers()
     */
    public function testModifyArgument(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(NumberHelper::class);
        $numberHelper = new NumberHelper();

        $numbers = [1, 2, 3, 4, 5];
        $expected = 15;
        $actual = $numberHelper->sumArray($numbers);
        $this->assertEquals($expected, $actual);

        $numbers = [1, 2, -3, 4, 5];
        $expected = 12;
        $actual = $numberHelper->sumArray($numbers);
        $this->assertEquals($expected, $actual);
    }
}
