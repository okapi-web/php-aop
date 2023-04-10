<?php

namespace Okapi\Aop\Service\Matcher\AdviceMatcher;

use Okapi\Aop\Attributes\AdviceType\InterceptionAdvice;
use Okapi\Aop\Attributes\Base\BaseAdvice;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod;

// todo remove?
class InterceptionMatcher
{
    /**
     * Match the advice.
     *
     * @param InterceptionAdvice $advice
     * @param ReflectionClass    $refClass
     *
     * @return InterceptionAdvice[]
     */
    public function match(
        InterceptionAdvice $advice,
        ReflectionClass $refClass,
    ): array {
        // TODO: Interface, Abstract class, Trait, ...

        // Check if the class matches
        if (!$advice->class->matches($refClass->getName())) {
            return [];
        }

        // Check if the methods match
        $advices = [];
        foreach ($refClass->getMethods() as $refMethod) {
            if ($advice->method->matches($refMethod->getName())) {
                $advice->setReflectionMethod($refMethod);
                $advices[] = $advice;
            }
        }

        return $advices;
    }
}
