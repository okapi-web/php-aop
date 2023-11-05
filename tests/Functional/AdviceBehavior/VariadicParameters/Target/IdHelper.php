<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\VariadicParameters\Target;


use Okapi\Aop\Tests\Functional\AdviceBehavior\VariadicParameters\Aspect\StringPrefixerAspect;

class IdHelper
{
    #[StringPrefixerAspect]
    public function createIds(string $prefix, string ...$ids): array
    {
        return $ids;
    }
}
