<?php

namespace Okapi\Aop\Tests\Functional\AdviceOptions\AdviceOnlyPublicMethods;

class TargetParentClass {
    public function parentHelloWorld()
    {
    }
    protected function parentHelloHere()
    {
    }
}

