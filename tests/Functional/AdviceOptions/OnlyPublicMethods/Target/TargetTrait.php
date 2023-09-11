<?php

namespace Okapi\Aop\Tests\Functional\AdviceOptions\OnlyPublicMethods\Target;

trait TargetTrait
{
    public function traitHelloWorld() {}

    protected function traitHelloHere() {}
}

