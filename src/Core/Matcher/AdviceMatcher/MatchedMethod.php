<?php

namespace Okapi\Aop\Core\Matcher\AdviceMatcher;

use Roave\BetterReflection\Reflection\ReflectionMethod as BetterReflectionMethod;

/**
 * # Matched Method
 *
 * This class is used to store matched method information.
 */
class MatchedMethod
{
    /**
     * MatchedMethod constructor.
     *
     * @param BetterReflectionMethod $matchedRefMethod
     */
    public function __construct(
        public BetterReflectionMethod $matchedRefMethod,
    ) {}
}
