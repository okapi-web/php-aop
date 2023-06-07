<?php

namespace Okapi\Aop\Tests\Functional\SelfType\ClassesToIntercept;

abstract class AbstractEmployee
{
    protected string $name;
    protected float $salary;

    abstract public function promote(AbstractEmployee|int $employee, float $salaryIncrease): self|int;

    abstract public function demote(Employee $employee, float $salaryDecrease): PartTimeEmployee;

    public function getName(): string
    {
        return $this->name;
    }

    public function getSalary(): float
    {
        return $this->salary;
    }
}
