<?php

namespace Okapi\Aop\Tests\Functional\MultipleExplicitMethodLevelAspects;

use Okapi\Aop\Tests\Functional\MultipleExplicitMethodLevelAspects\Aspect\SecurityAspect;
use Okapi\Aop\Tests\Functional\MultipleExplicitMethodLevelAspects\ClassesToIntercept\AccountService;
use Okapi\Aop\Tests\Functional\MultipleExplicitMethodLevelAspects\ClassesToIntercept\TransactionService;
use Okapi\Aop\Tests\Stubs\Kernel\EmptyKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class MultipleExplicitMethodLevelAspectsTest extends TestCase
{
    /**
     * @see SecurityAspect::applySecurityMeasures()
     */
    public function testMultipleExplicitMethodLevelAspects(): void
    {
        Util::clearCache();
        EmptyKernel::init();

        $id = '1234567890';

        $accountService = new AccountService();

        $accountService->createAccount(['id' => $id]);

        $accounts = $accountService->getAccounts();
        $this->assertCount(1, $accounts);

        $firstAccount = $accounts[0];
        $this->assertStringEndsWith(SecurityAspect::SECRET_HASH, $firstAccount);

        /** @noinspection PhpUnhandledExceptionInspection */
        $accountService->deleteAccount($id);

        $accounts = $accountService->getAccounts();
        $this->assertCount(0, $accounts);


        $transactionService = new TransactionService();

        $transactionService->createTransaction(['id' => $id]);

        $transactions = $transactionService->getTransactions();
        $this->assertCount(1, $transactions);

        $firstTransaction = $transactions[0];
        $this->assertStringEndsWith(SecurityAspect::SECRET_HASH, $firstTransaction);

        /** @noinspection PhpUnhandledExceptionInspection */
        $transactionService->rollbackTransaction($id);

        $transactions = $transactionService->getTransactions();
        $this->assertCount(0, $transactions);
    }
}
