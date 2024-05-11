<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\Readonly\Target;

class ReadonlyPromotedProperties
{
    public function __construct(
        public readonly string $name,
        public readonly int $age,
    ) {}
}
