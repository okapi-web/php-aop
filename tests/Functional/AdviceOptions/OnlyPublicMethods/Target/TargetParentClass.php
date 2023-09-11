<?php

namespace Okapi\Aop\Tests\Functional\AdviceOptions\OnlyPublicMethods\Target;

class TargetParentClass
{
    public function parentHelloWorld() {}

    protected function parentHelloHere() {}
}

