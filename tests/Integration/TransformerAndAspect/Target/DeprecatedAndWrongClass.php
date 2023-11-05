<?php

namespace Okapi\Aop\Tests\Integration\TransformerAndAspect\Target;

class DeprecatedAndWrongClass
{
    public function checkIfFloat(mixed $value): bool
    {
        /** @noinspection PhpDeprecationInspection */
        return !is_real($value);
    }
}
