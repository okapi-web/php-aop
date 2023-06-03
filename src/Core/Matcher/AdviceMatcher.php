<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Core\Matcher;

use DI\Attribute\Inject;
use Okapi\Aop\Core\Container\AdviceContainer;
use Okapi\Aop\Core\Container\AdviceType\MethodAdviceContainer;
use Okapi\Aop\Core\Matcher\AdviceMatcher\MethodMatcher;
use Roave\BetterReflection\Reflection\ReflectionClass as BetterReflectionClass;

/**
 * # Advice Matcher
 *
 * This class is used to match the advices for the given class.
 */
class AdviceMatcher
{
    // region DI

    #[Inject]
    private MethodMatcher $methodMatcher;

    // endregion

    /**
     * Match the given advice container with the given class.
     *
     * @param AdviceContainer       $adviceContainer
     * @param BetterReflectionClass $refClass
     *
     * @return AdviceContainer|null
     *
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    public function match(
        AdviceContainer       $adviceContainer,
        BetterReflectionClass $refClass,
    ): ?AdviceContainer {
        /** @noinspection PhpSwitchStatementWitSingleBranchInspection */
        switch (true) {
            /** @noinspection PhpConditionAlreadyCheckedInspection */
            case $adviceContainer instanceof MethodAdviceContainer:
                return $this->methodMatcher->match(
                    $adviceContainer,
                    $refClass,
                );
        }
    }
}
