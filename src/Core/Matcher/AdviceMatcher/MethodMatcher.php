<?php

namespace Okapi\Aop\Core\Matcher\AdviceMatcher;

use Okapi\Aop\Core\Container\AdviceType\MethodAdviceContainer;
use Roave\BetterReflection\Reflection\ReflectionClass;

/**
 * # Method Matcher
 *
 * This class is used to match the given method advice container for the given
 * class.
 */
class MethodMatcher
{
    /**
     * Match the given method advice container for the given class.
     *
     * @param MethodAdviceContainer $methodAdviceContainer
     * @param ReflectionClass       $refClassToMatch
     *
     * @return MethodAdviceContainer|null
     */
    public function match(
        MethodAdviceContainer $methodAdviceContainer,
        ReflectionClass       $refClassToMatch,
    ): ?MethodAdviceContainer {
        $adviceAttributeInstance  =
            $methodAdviceContainer->adviceAttributeInstance;
        $newMethodAdviceContainer = null;

        foreach ($refClassToMatch->getMethods() as $refMethodToMatch) {
            // Check for explicit match
            if ($methodAdviceContainer->isExplicit()) {
                // Advices without a method name are matched for all methods
                if ($adviceAttributeInstance->method === null) {
                    if (!$newMethodAdviceContainer) {
                        $newMethodAdviceContainer = clone $methodAdviceContainer;
                    }

                    $newMethodAdviceContainer->addMatchedMethod($refMethodToMatch);
                }
            } else {
                // Check for implicit match
                $methodNameToMatch = $refMethodToMatch->getName();

                if ($adviceAttributeInstance->method->matches($methodNameToMatch)) {
                    if (!$newMethodAdviceContainer) {
                        $newMethodAdviceContainer = clone $methodAdviceContainer;
                    }

                    $newMethodAdviceContainer->addMatchedMethod($refMethodToMatch);
                }
            }
        }

        return $newMethodAdviceContainer;
    }
}
