<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ProtectedAndPrivateMethods;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ProtectedAndPrivateMethods\Aspect\BankingAspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ProtectedAndPrivateMethods\Target\BankingSystem;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class ProtectedAndPrivateMethodsTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see BankingAspect::removeFeeFromDeposit()
     * @see BankingAspect::removeFeeFromWithdraw()
     */
    public function testProtectedAndPrivateMethods(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(BankingSystem::class);
        $bankingSystem = new BankingSystem();

        $bankingSystem->deposit(100.0);
        $balance = $bankingSystem->getBalance();

        $this->assertEquals(
            100.0,
            $balance,
        );

        $bankingSystem->withdraw(50.0);
        $balance = $bankingSystem->getBalance();

        $this->assertEquals(
            50.0,
            $balance,
        );
    }
}
