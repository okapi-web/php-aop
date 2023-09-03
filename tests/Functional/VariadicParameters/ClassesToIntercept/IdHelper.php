<?php

namespace Okapi\Aop\Tests\Functional\VariadicParameters\ClassesToIntercept;


use Okapi\Aop\Tests\Functional\VariadicParameters\Aspect\StringPrefixerAspect;

class IdHelper
{
    #[StringPrefixerAspect]
    public function createIds(string $prefix, string ...$ids): array
    {
        return $ids;
    }
}
