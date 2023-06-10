<?php

namespace Okapi\Aop\Core\Matcher\AdviceMatcher;

use Okapi\Aop\Core\Container\AdviceType\MethodAdviceContainer;
use Roave\BetterReflection\Reflection\ReflectionClass as BetterReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod as BetterReflectionMethod;

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
     * @param BetterReflectionClass $refClassToMatch
     *
     * @return MethodAdviceContainer|null
     */
    public function match(
        MethodAdviceContainer $methodAdviceContainer,
        BetterReflectionClass $refClassToMatch,
    ): ?MethodAdviceContainer {
        $newMethodAdviceContainer = null;

        foreach ($refClassToMatch->getMethods() as $refMethodToMatch) {
            // Match explicit aspects
            if ($methodAdviceContainer->isExplicit()) {
                $newMethodAdviceContainer = $this->matchExplicit(
                    $methodAdviceContainer,
                    $refMethodToMatch,
                    $newMethodAdviceContainer,
                );
            } else {
                // Match implicit aspects
                $newMethodAdviceContainer = $this->matchImplicit(
                    $methodAdviceContainer,
                    $refMethodToMatch,
                    $newMethodAdviceContainer,
                );
            }
        }

        return $newMethodAdviceContainer;
    }

    /**
     * Match explicit aspects.
     *
     * @param MethodAdviceContainer      $methodAdviceContainer
     * @param BetterReflectionMethod     $refMethodToMatch
     * @param MethodAdviceContainer|null $newMethodAdviceContainer
     *
     * @return MethodAdviceContainer|null
     */
    protected function matchExplicit(
        MethodAdviceContainer $methodAdviceContainer,
        BetterReflectionMethod $refMethodToMatch,
        ?MethodAdviceContainer $newMethodAdviceContainer,
    ): ?MethodAdviceContainer {
        $aspectClassName = $methodAdviceContainer->aspectClassName;

        // Only add methods that have the attribute of the aspect
        foreach ($refMethodToMatch->getAttributes() as $refAttribute) {
            if ($refAttribute->getName() === $aspectClassName) {
                $newMethodAdviceContainer = $this->createNewMethodAdviceContainer(
                    $methodAdviceContainer,
                    $newMethodAdviceContainer,
                );

                $newMethodAdviceContainer->addMatchedMethod($refMethodToMatch);
            }
        }

        return $newMethodAdviceContainer;
    }

    /**
     * Match implicit aspects.
     *
     * @param MethodAdviceContainer      $methodAdviceContainer
     * @param BetterReflectionMethod     $refMethodToMatch
     * @param MethodAdviceContainer|null $newMethodAdviceContainer
     *
     * @return MethodAdviceContainer|null
     */
    protected function matchImplicit(
        MethodAdviceContainer $methodAdviceContainer,
        BetterReflectionMethod $refMethodToMatch,
        ?MethodAdviceContainer $newMethodAdviceContainer,
    ): ?MethodAdviceContainer {
        $methodNameToMatch = $refMethodToMatch->getName();

        if ($methodAdviceContainer->adviceAttributeInstance->method->matches($methodNameToMatch)) {
            $newMethodAdviceContainer = $this->createNewMethodAdviceContainer(
                $methodAdviceContainer,
                $newMethodAdviceContainer,
            );

            $newMethodAdviceContainer->addMatchedMethod($refMethodToMatch);
        }

        return $newMethodAdviceContainer;
    }

    /**
     * Create a new method advice container or return the given one.
     *
     * This method clones the existing method advice container to keep
     * track of the matched methods.
     *
     * @param MethodAdviceContainer      $methodAdviceContainer
     * @param MethodAdviceContainer|null $newMethodAdviceContainer
     *
     * @return MethodAdviceContainer
     */
    protected function createNewMethodAdviceContainer(
        MethodAdviceContainer $methodAdviceContainer,
        ?MethodAdviceContainer $newMethodAdviceContainer,
    ): MethodAdviceContainer {
        return $newMethodAdviceContainer ?? clone $methodAdviceContainer;
    }
}
