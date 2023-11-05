<?php

namespace Okapi\Aop\Tests\Functional\AspectMatching\SelfType;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AspectMatching\SelfType\Aspect\SalaryIncreaserAspect;
use Okapi\Aop\Tests\Functional\AspectMatching\SelfType\Target\AbstractEmployee;
use Okapi\Aop\Tests\Functional\AspectMatching\SelfType\Target\Employee;
use Okapi\Aop\Tests\Functional\AspectMatching\SelfType\Target\PartTimeEmployee;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class SelfTypeTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see SalaryIncreaserAspect::increaseSalary()
     */
    public function testSelfType(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(Employee::class);
        $this->assertWillBeWoven(AbstractEmployee::class);
        $employee = new Employee('Walter', 3000.0);

        $salaryIncrease = 1000.0;

        $promotedEmployee = $employee->promote($employee, $salaryIncrease);

        $this->assertInstanceOf(Employee::class, $promotedEmployee);
        $this->assertInstanceOf(AbstractEmployee::class, $promotedEmployee);
        $this->assertSame(
            $employee->getName(),
            $promotedEmployee->getName(),
        );
        $this->assertSame(
            $employee->getSalary() + ($salaryIncrease * 2),
            $promotedEmployee->getSalary(),
        );


        $salaryDecrease = 1000.0;

        $demotedEmployee = $promotedEmployee->demote($promotedEmployee, $salaryDecrease);

        $this->assertInstanceOf(PartTimeEmployee::class, $demotedEmployee);
        $this->assertInstanceOf(Employee::class, $demotedEmployee);
        $this->assertInstanceOf(AbstractEmployee::class, $demotedEmployee);
        $this->assertSame(
            $promotedEmployee->getName(),
            $demotedEmployee->getName(),
        );
        $this->assertSame(
            $promotedEmployee->getSalary() - $salaryDecrease,
            $demotedEmployee->getSalary(),
        );
    }
}
