<?php

namespace Okapi\Aop\Tests\Stubs\ClassesToIntercept\TransformerAndAspect;

class DeprecatedAndWrongClass
{
    public function checkIfFloat(mixed $value): bool
    {
        /** @noinspection PhpDeprecationInspection */
        return !is_real($value);
    }
}
