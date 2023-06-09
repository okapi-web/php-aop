<?php

namespace Okapi\Aop\Tests\Functional\ProtectedAndPrivateMethods;

use Okapi\Aop\Tests\Functional\ProtectedAndPrivateMethods\Aspect\BankingAspect;
use Okapi\Aop\Tests\Functional\ProtectedAndPrivateMethods\ClassesToIntercept\BankingSystem;
use Okapi\Aop\Tests\Stubs\Kernel\ApplicationKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class ProtectedAndPrivateMethodsTest extends TestCase
{
    /**
     * @see BankingAspect::removeFeeFromDeposit()
     * @see BankingAspect::removeFeeFromWithdraw()
     */
    public function testProtectedAndPrivateMethods(): void
    {
        Util::clearCache();
        ApplicationKernel::init();

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
