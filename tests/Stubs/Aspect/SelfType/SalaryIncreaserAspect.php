<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Stubs\Aspect\SelfType;

use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Attributes\Before;
use Okapi\Aop\Invocation\BeforeMethodInvocation;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\SelfType\AbstractEmployee;

#[Aspect]
class SalaryIncreaserAspect
{
    #[Before(
        class: AbstractEmployee::class,
        method: 'promote',
    )]
    public function increaseSalary(BeforeMethodInvocation $invocation): void
    {
        $salary = $invocation->getArgument('salaryIncrease');

        $invocation->setArgument(
            'salaryIncrease',
            $salary * 2,
        );
    }
}
