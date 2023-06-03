<?php

namespace Okapi\Aop\Tests\Stubs\ClassesToIntercept\SelfType;

class Employee extends AbstractEmployee
{
    public function __construct(
        protected string $name,
        protected float $salary,
    ) {}

    public function promote(AbstractEmployee|int $employee, float $salaryIncrease): self|int
    {
        $promotedSalary = $employee->getSalary() + $salaryIncrease;

        return new self($employee->getName(), $promotedSalary);
    }

    public function demote(Employee $employee, float $salaryDecrease): PartTimeEmployee
    {
        $demotedSalary = $employee->getSalary() - $salaryDecrease;

        return new PartTimeEmployee($employee->getName(), $demotedSalary);
    }
}
