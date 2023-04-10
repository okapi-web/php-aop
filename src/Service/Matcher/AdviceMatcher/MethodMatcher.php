<?php

namespace Okapi\Aop\Service\Matcher\AdviceMatcher;

use Okapi\Aop\Attributes\Base\MethodAdvice;
use Okapi\Aop\Container\MethodAdviceContainer;
use Okapi\CodeTransformer\Service\DI;
use Roave\BetterReflection\Reflection\ReflectionClass;

// TODO docs
class MethodMatcher
{
    /**
     * Match the given advice for the given class.
     *
     * @param MethodAdvice    $advice
     * @param ReflectionClass $refClass
     *
     * @return MethodAdviceContainer[]
     */
    public function match(MethodAdvice $advice, ReflectionClass $refClass): array
    {
        $matchedMethodAdvices = [];

        foreach ($refClass->getMethods() as $refMethod) {
            if ($advice->method->matches($refMethod->getName())) {
                $methodAdviceContainer = DI::make(MethodAdviceContainer::class, [
                    'filePath'  => $refClass->getFileName(),
                    'advice'    => $advice,
                    'refMethod' => $refMethod,
                ]);

                $matchedMethodAdvices[] = $methodAdviceContainer;
            }
        }

        return $matchedMethodAdvices;
    }
}
