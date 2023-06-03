<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Core\Matcher;

use DI\Attribute\Inject;
use Okapi\Aop\Core\Container\AdviceContainer;
use Okapi\Aop\Core\Container\AdviceType\MethodAdviceContainer;
use Okapi\Aop\Core\Container\AspectManager;
use Okapi\CodeTransformer\Core\Cache\CacheState;
use Okapi\CodeTransformer\Core\Cache\CacheState\EmptyResultCacheState;
use Okapi\CodeTransformer\Core\Cache\CacheStateManager;
use Okapi\CodeTransformer\Core\DI;
use Okapi\CodeTransformer\Core\Util\ReflectionHelper;
use Okapi\Path\Path;

/**
 * # Aspect Matcher
 *
 * This class is used to match the advices for the given class.
 */
class AspectMatcher
{
    // region DI

    #[Inject]
    private AspectManager $aspectContainer;

    #[Inject]
    private ReflectionHelper $reflectionHelper;

    #[Inject]
    private CacheStateManager $cacheStateManager;

    #[Inject]
    private ClassMatcher $classMatcher;

    #[Inject]
    private AdviceMatcher $adviceMatcher;

    // endregion

    /**
     * List of matched advice containers for the given class.
     *
     * @var array<class-string, AdviceContainer[]>
     */
    private array $matchedAdviceContainers = [];

    /**
     * Match advices for the given class by the given class loader.
     *
     * @param class-string $namespacedClass
     *
     * @return bool
     */
    public function matchByClassLoader(
        string $namespacedClass,
    ): bool {
        // Get the aspects
        $aspectAdviceContainers = $this->aspectContainer->getAspectAdviceContainers();

        // Get the reflection class
        $refClass = $this->reflectionHelper->getReflectionClass(
            $namespacedClass,
        );

        // Skip interfaces and traits
        if ($refClass->isInterface() || $refClass->isTrait()) {
            $this->cacheEmptyResult(
                $namespacedClass,
                $refClass->getFileName(),
            );
            return false;
        }

        // Match the advices
        $matchedAdviceContainers = [];
        foreach ($aspectAdviceContainers as $aspectAdviceContainer) {
            foreach ($aspectAdviceContainer as $adviceContainer) {
                $adviceAttributeInstance = $adviceContainer->adviceAttributeInstance;
                $classRegex              = $adviceAttributeInstance->class;

                $classMatches = $classRegex->matches($namespacedClass);

                $interfacesMatches = $this->classMatcher->matchInterfaces(
                    $classRegex,
                    $refClass,
                );

                $parentClassesMatches = $this->classMatcher->matchParentClasses(
                    $classRegex,
                    $refClass,
                );

                $traitsMatches = $this->classMatcher->matchTraits(
                    $classRegex,
                    $refClass,
                );

                // If none of the matches are true, skip the class
                if (!($classMatches
                    || $interfacesMatches
                    || $parentClassesMatches
                    || $traitsMatches
                )) {
                    continue;
                }

                // Match advices
                $matchedAdviceContainer = $this->adviceMatcher->match(
                    $adviceContainer,
                    $refClass,
                );
                if ($matchedAdviceContainer) {
                    $matchedAdviceContainers[] = $matchedAdviceContainer;
                }
            }
        }

        // Cache the result
        $this->matchedAdviceContainers[$namespacedClass] = $matchedAdviceContainers;

        // Cache the result
        if (!$matchedAdviceContainers) {
            $this->cacheEmptyResult(
                $namespacedClass,
                $refClass->getFileName(),
            );
        }

        return (bool)$matchedAdviceContainers;
    }

    /**
     * Cache the empty result.
     *
     * @param string $namespacedClass
     * @param string $filePath
     *
     * @return void
     */
    private function cacheEmptyResult(
        string $namespacedClass,
        string $filePath,
    ): void {
        $filePath   = Path::resolve($filePath);
        $cacheState = DI::make(EmptyResultCacheState::class, [
            CacheState::DATA => [
                CacheState::ORIGINAL_FILE_PATH_KEY => $filePath,
                CacheState::NAMESPACED_CLASS_KEY   => $namespacedClass,
                CacheState::MODIFICATION_TIME_KEY  => filemtime($filePath),
            ],
        ]);

        // Set the cache state
        $this->cacheStateManager->setCacheState(
            $filePath,
            $cacheState,
        );
    }

    /**
     * Add matched advice containers for the given class.
     *
     * @param class-string      $namespacedClass
     * @param AdviceContainer[] $adviceContainers
     *
     * @return void
     */
    public function addMatchedAdviceContainers(
        string $namespacedClass,
        array  $adviceContainers,
    ): void {
        $this->matchedAdviceContainers[$namespacedClass] = $adviceContainers;
    }

    /**
     * Get matched advice containers for the given class.
     *
     * @param class-string $namespacedClass
     *
     * @return AdviceContainer[]
     */
    public function getMatchedAdviceContainers(string $namespacedClass): array
    {
        return $this->matchedAdviceContainers[$namespacedClass] ?? [];
    }

    /**
     * Get matched advice containers by the given join point.
     *
     * @param class-string $targetClassName
     * @param string       $joinPoint
     *
     * @return MethodAdviceContainer[]
     */
    public function getMatchedAdviceContainersByJoinPoint(
        string $targetClassName,
        string $joinPoint,
    ): array {
        $matchedAdviceContainers = [];

        foreach ($this->matchedAdviceContainers[$targetClassName] as $adviceContainer) {
            if (!($adviceContainer instanceof MethodAdviceContainer)) {
                continue;
            }

            if ($adviceContainer->getName() === $joinPoint) {
                $matchedAdviceContainers[] = $adviceContainer;
            }
        }

        return $matchedAdviceContainers;
    }
}
