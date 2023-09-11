<?php

namespace Okapi\Aop\Tests\Functional\AdviceOptions\InterceptTraitMethods\Target;

class TargetClass
{
    use TargetTrait;

    public function helloWorld() {}
}
