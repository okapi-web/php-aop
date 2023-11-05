<?php

namespace Okapi\Aop\Tests\Functional\AspectMatching\InterceptTraitMethods\Target;

class TargetClass
{
    use TargetTrait;

    public function helloWorld() {}
}
