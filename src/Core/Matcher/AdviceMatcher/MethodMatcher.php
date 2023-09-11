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

        $refClassToMatchName = $refClassToMatch->getName();

        foreach ($refClassToMatch->getMethods() as $refMethodToMatch) {
            // Basically the same as $refClassToMatch->getImmediateMethods(),
            // but this also includes the methods from traits, because traits
            // cannot be woven
            $declaringClass     = $refMethodToMatch->getDeclaringClass();
            $declaringClassName = $declaringClass->getName();
            if (
                !$declaringClass->isTrait()
                && $declaringClassName !== $refClassToMatchName
            ) {
                continue;
            }

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

        // Match class attributes
        $declaringClass = $refMethodToMatch->getDeclaringClass();
        foreach ($declaringClass->getAttributes() as $refAttribute) {
            if ($refAttribute->getName() === $aspectClassName) {
                $adviceAttributeInstance = $methodAdviceContainer->adviceAttributeInstance;

                // Advices without method are applied to all methods
                if ($adviceAttributeInstance->method === null) {
                    $newMethodAdviceContainer = $this->createNewMethodAdviceContainer(
                        $methodAdviceContainer,
                        $newMethodAdviceContainer,
                    );

                    $newMethodAdviceContainer->addMatchedMethod($refMethodToMatch);
                } else {
                    $methodNameToMatch = $refMethodToMatch->getName();
                    $methodRegex       = $adviceAttributeInstance->method;

                    if ($methodRegex->matches($methodNameToMatch)) {
                        $newMethodAdviceContainer = $this->createNewMethodAdviceContainer(
                            $methodAdviceContainer,
                            $newMethodAdviceContainer,
                        );

                        $newMethodAdviceContainer->addMatchedMethod($refMethodToMatch);
                    }
                }
            }
        }

        // Match method attributes
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

        if (
            $methodAdviceContainer->adviceAttributeInstance->bypassParentMethods
            &&  in_array(
                $refMethodToMatch->getImplementingClass()->getName(),
                $refMethodToMatch->getCurrentClass()->getParentClassNames()
            )
        ) {
            // bypass parent classes
            return $newMethodAdviceContainer;
        }

        if (
            $methodAdviceContainer->adviceAttributeInstance->bypassTraitMethods
            && $refMethodToMatch->getDeclaringClass()->isTrait()
        ) {
            // bypass used traits
            return $newMethodAdviceContainer;
        }

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
