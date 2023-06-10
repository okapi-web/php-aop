<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Core\Matcher;

use Attribute;
use DI\Attribute\Inject;
use Okapi\Aop\AopKernel;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Core\Container\AdviceContainer;
use Okapi\Aop\Core\Container\AdviceType\MethodAdviceContainer;
use Okapi\Aop\Core\Container\AspectManager;
use Okapi\CodeTransformer\Core\Cache\CacheState;
use Okapi\CodeTransformer\Core\Cache\CacheState\EmptyResultCacheState;
use Okapi\CodeTransformer\Core\Cache\CacheStateManager;
use Okapi\CodeTransformer\Core\DI;
use Okapi\CodeTransformer\Core\Util\ReflectionHelper;
use Okapi\Path\Path;
use Roave\BetterReflection\Reflection\ReflectionClass as BetterReflectionClass;

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

    #[Inject]
    private AspectManager $aspectManager;

    // endregion

    /**
     * List of matched advice containers for the given class.
     *
     * @var array<class-string, AdviceContainer[]>
     */
    private array $matchedAdviceContainers = [];

    /**
     * List of explicit class aspect targets.
     *
     * @var array<class-string, bool> Key is the class name, value is true if
     *                                the class has explicit aspect targets.
     */
    private array $explicitClassAspectTargets = [];

    /**
     * List of explicit method aspect targets.
     *
     * @var array<class-string, string[]> Key is the class name, value is the
     *                                    list of explicit aspect targets.
     */
    private array $explicitMethodAspectTargets = [];

    /**
     * Match advices for the given class by the given class loader.
     *
     * @param class-string $namespacedClass
     *
     * @return bool
     */
    public function matchByClassLoader(string $namespacedClass): bool
    {
        // Get the reflection class
        $refClass = $this->reflectionHelper->getReflectionClass($namespacedClass);

        // Check for explicit class/method-level aspects
        $this->checkForExplicitAdvices($refClass);

        // Get the aspects
        $aspectAdviceContainers = $this->aspectContainer->getAspectAdviceContainers();

        // Skip interfaces and traits
        if ($refClass->isInterface() || $refClass->isTrait()) {
            $this->cacheEmptyResult(
                $namespacedClass,
                $refClass->getFileName(),
            );
            return false;
        }

        // Match the advices from the aspects
        $matchedAdviceContainers = [];
        foreach ($aspectAdviceContainers as $aspectAdviceContainer) {
            foreach ($aspectAdviceContainer as $adviceContainer) {
                // Match class, interfaces, traits and parent classes
                if (!$this->classMatcher->match(
                    $refClass,
                    $adviceContainer,
                    $this->explicitClassAspectTargets[$namespacedClass] ?? false,
                    (bool)$this->explicitMethodAspectTargets[$namespacedClass],
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
     * Check for explicit advices and register them.
     *
     * Explicit advices don't need to be added to the {@link AopKernel},
     * because they will be registered here at runtime.
     *
     * @param BetterReflectionClass $refClass
     *
     * @return void
     */
    protected function checkForExplicitAdvices(
        BetterReflectionClass $refClass,
    ): void {
        // Check for explicit class-level aspects
        foreach ($refClass->getAttributes() as $attribute) {
            $attributeClass        = $attribute->getClass();
            $hasAspectAttribute    = (bool)$attributeClass->getAttributesByInstance(Aspect::class);
            $hasAttributeAttribute = (bool)$attributeClass->getAttributesByInstance(Attribute::class);

            if ($hasAspectAttribute && $hasAttributeAttribute) {
                $this->aspectManager->loadAspect($attributeClass->getName());

                $this->explicitClassAspectTargets[$refClass->getName()] = true;
            }
        }

        // Check for explicit method-level aspects
        foreach ($refClass->getImmediateMethods() as $refMethod) {
            foreach ($refMethod->getAttributes() as $attribute) {
                $attributeClass        = $attribute->getClass();
                $hasAspectAttribute    = (bool)$attributeClass->getAttributesByInstance(Aspect::class);
                $hasAttributeAttribute = (bool)$attributeClass->getAttributesByInstance(Attribute::class);

                if ($hasAspectAttribute && $hasAttributeAttribute) {
                    $this->aspectManager->loadAspect($attributeClass->getName());

                    $this->explicitMethodAspectTargets[$refClass->getName()][] = $refMethod->getName();
                }
            }
        }
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
