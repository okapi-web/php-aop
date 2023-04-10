<?php

namespace Okapi\Aop\Service\Matcher;

use Okapi\Aop\Attributes\Base\BaseAdvice;
use Okapi\Aop\Attributes\Base\MethodAdvice;
use Okapi\Aop\Container\AdviceContainer;
use Okapi\Aop\Container\AspectContainer;
use Okapi\Aop\Service\Matcher\AdviceMatcher\MethodMatcher;
use Okapi\Aop\Util\ReflectionHelper;
use Okapi\CodeTransformer\Service\DI;

// TODO: docs
class AspectMatcher
{
    /**
     * Cache for the query results of the advice matching for a class.
     *
     * @var array{class-string: BaseAdvice[]}
     */
    private array $adviceClassQueryCache = [];

    /**
     * Cache for the query results of the aspect matching.
     *
     * @var array{class-string: AdviceContainer[]}
     */
    private array $adviceQueryCache = [];

    /**
     * Match advices for the given class.
     *
     * @param string $className
     *
     * @return BaseAdvice[]
     */
    public function matchClass(string $className): array
    {
        // Check if the query has been cached
        if (isset($this->adviceClassQueryCache[$className])) {
            return $this->adviceClassQueryCache[$className];
        }

        // Get the aspect targets
        $aspectContainer = DI::get(AspectContainer::class);
        $aspectTargets   = $aspectContainer->getAdvices();

        // Match the aspect targets
        foreach ($aspectTargets as $aspectTarget) {
            if ($aspectTarget->class->matches($className)) {
                $this->adviceClassQueryCache[$className][] = $aspectTarget;
            }
        }

        return $this->adviceClassQueryCache[$className] ?? [];
    }

    /**
     * Match advices for the given class.
     *
     * @param string $namespacedClass
     *
     * @return AdviceContainer[]
     */
    public function matchAdvices(string $namespacedClass): array
    {
        // Check if the query has been cached
        if (isset($this->adviceQueryCache[$namespacedClass])) {
            return $this->adviceQueryCache[$namespacedClass];
        }

        // Get the aspect container
        $aspectContainer = DI::get(AspectContainer::class);
        $advices         = $aspectContainer->getAdvices();

        // Get the reflection class
        $reflectionHelper = DI::get(ReflectionHelper::class);
        $refClass         = $reflectionHelper->getReflectionClass($namespacedClass);

        // Match the aspect targets
        $matchedAdvices = [];
        foreach ($advices as $advice) {
            // Method advice
            if ($advice instanceof MethodAdvice) {
                $methodMatcher = DI::get(MethodMatcher::class);

                $matchedAdvices = array_merge(
                    $matchedAdvices,
                    $methodMatcher->match(
                        $advice,
                        $refClass,
                    )
                );
            }
        }

        // Cache the query result
        $this->adviceQueryCache[$namespacedClass] = $matchedAdvices;

        return $matchedAdvices;
    }
}
