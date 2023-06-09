<?php

namespace Okapi\Aop\Tests\Functional\TransformerAndAspect\ClassesToIntercept;

class DeprecatedAndWrongClass
{
    public function checkIfFloat(mixed $value): bool
    {
        /** @noinspection PhpDeprecationInspection */
        return !is_real($value);
    }
}
