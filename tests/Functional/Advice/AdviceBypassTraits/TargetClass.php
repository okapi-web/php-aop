<?php

namespace Okapi\Aop\Tests\Functional\Advice\AdviceBypassTraits;

class TargetClass {
    use TargetTrait ;
    public function helloWorld()
    {
    }
}

