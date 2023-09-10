<?php

namespace Okapi\Aop\Tests\Functional\Advice\AdviceOnlyPublic;

class TargetClass {
    public function helloWorld()
    {
    }
    protected function helloHere()
    {
    }
}

