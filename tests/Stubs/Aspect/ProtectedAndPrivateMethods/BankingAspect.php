<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Stubs\Aspect\ProtectedAndPrivateMethods;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\ProtectedAndPrivateMethods\BankingSystem;

#[Aspect]
class BankingAspect
{
    #[After(
        BankingSystem::class,
        'removeFeeFromDeposit',
    )]
    public function removeFeeFromDeposit(AfterMethodInvocation $invocation): void
    {
        $result = $invocation->proceed();
        $result = $result / (1 - BankingSystem::DEPOSIT_FEE_PERCENTAGE / 100);

        $invocation->setResult($result);
    }

    #[After(
        BankingSystem::class,
        'addFeeToWithdraw',
    )]
    public function removeFeeFromWithdraw(AfterMethodInvocation $invocation): void
    {
        $result = $invocation->proceed();
        $result = $result / (1 + BankingSystem::WITHDRAW_FEE_PERCENTAGE / 100);

        $invocation->setResult($result);
    }
}
