<?php

namespace Okapi\Aop\Tests\Functional\AdviceOptions\AdviceOnlyPublicMethods;

class TargetClass extends TargetParentClass {
    use TargetTrait ;
    public function helloWorld()
    {
    }
    protected function helloHere()
    {
    }
    public function askParentHelloHere()
    {
        parent::parentHelloHere();
    }
    public function askTraitHelloHere()
    {
        $this->traitHelloHere();
    }
}
