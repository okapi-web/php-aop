<?php

namespace Okapi\Aop\Tests\Functional\AdviceOptions\AdviceOnlyPublicMethods;

trait TargetTrait {
    public function traitHelloWorld()
    {
    }
    protected function traitHelloHere()
    {
    }
}

