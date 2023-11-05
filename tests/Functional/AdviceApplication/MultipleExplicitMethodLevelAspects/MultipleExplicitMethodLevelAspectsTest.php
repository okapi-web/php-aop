<?php

namespace Okapi\Aop\Tests\Functional\AdviceApplication\MultipleExplicitMethodLevelAspects;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceApplication\MultipleExplicitMethodLevelAspects\Aspect\SecurityAspect;
use Okapi\Aop\Tests\Functional\AdviceApplication\MultipleExplicitMethodLevelAspects\Target\AccountService;
use Okapi\Aop\Tests\Functional\AdviceApplication\MultipleExplicitMethodLevelAspects\Target\TransactionService;
use Okapi\Aop\Tests\Stubs\Kernel\EmptyKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class MultipleExplicitMethodLevelAspectsTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see SecurityAspect::applySecurityMeasures()
     */
    public function testMultipleExplicitMethodLevelAspects(): void
    {
        Util::clearCache();
        EmptyKernel::init();

        $id = '1234567890';

        $this->assertWillBeWoven(AccountService::class);
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


        $this->assertWillBeWoven(TransactionService::class);
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
