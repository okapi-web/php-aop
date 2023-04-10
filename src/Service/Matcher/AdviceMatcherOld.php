<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Service\Matcher;

use Composer\Autoload\ClassLoader;
use Okapi\Aop\Attributes\AdviceType\InterceptionAdvice;
use Okapi\Aop\Attributes\Base\BaseAdvice;
use Okapi\Aop\Container\AspectContainer;
use Okapi\Aop\Service\Matcher\AdviceMatcher\InterceptionMatcher;
use Okapi\CodeTransformer\Service\DI;
use Okapi\CodeTransformer\Service\Matcher\TransformerMatcher;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass as BetterReflectionClass;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\ComposerSourceLocator;

// TODO: remove
// TODO: include transformer matcher
class AdviceMatcherOld extends TransformerMatcher
{
    /**
     * Cache for the query result of the advice matching.
     *
     * @var array<class-string, BaseAdvice[]>
     */
    private array $adviceQueryResultCache = [];

    /**
     * Composer class loader.
     *
     * @var ClassLoader|null
     */
    private ?ClassLoader $classLoader = null;

    /**
     * Set class loader.
     *
     * @param ClassLoader $classLoader
     *
     * @return void
     */
    public function setClassLoader(ClassLoader $classLoader): void
    {
        $this->classLoader = $classLoader;
    }

    /**
     * @inheritDoc
     */
    public function shouldTransform(string $namespacedClass): bool
    {
        return $this->match($namespacedClass) !== []
            || parent::shouldTransform($namespacedClass);
    }

    /**
     * Match advices for the given class.
     *
     * @param string $namespacedClass
     *
     * @return BaseAdvice[]
     */
    public function match(string $namespacedClass): array
    {
        // Check if the query has been cached
        if (isset($this->adviceQueryResultCache[$namespacedClass])) {
            return $this->adviceQueryResultCache[$namespacedClass];
        }

        // Get reflection
        $refClass = $this->getReflectionClass($namespacedClass);

        // Get aspect targets
        static $aspectContainer;
        if (!isset($aspectContainer)) {
            $aspectContainer = DI::get(AspectContainer::class);
        }
        $aspectTargets = $aspectContainer->getAdvices();

        // Match advices
        $matchedAdvices = [];
        foreach ($aspectTargets as $advice) {
            if ($advice instanceof InterceptionAdvice) {
                $matchedAdvices = array_merge(
                    $matchedAdvices,
                    $this->matchInterceptionAdvice($advice, $refClass)
                );
            }
        }

        // Cache the query result
        $this->adviceQueryResultCache[$namespacedClass] = $matchedAdvices;

        return $matchedAdvices;
    }

    /**
     * Get reflection class.
     *
     * @param string $class
     *
     * @return BetterReflectionClass
     */
    private function getReflectionClass(string $class): BetterReflectionClass
    {
        static $astLocator, $reflector;

        if (!isset($astLocator, $reflector)) {
            $astLocator  = (new BetterReflection)->astLocator();
            $reflector   = new DefaultReflector(
                new ComposerSourceLocator($this->classLoader, $astLocator),
            );
        }

        return $reflector->reflectClass($class);
    }

    /**
     * Match interception advice.
     *
     * @param InterceptionAdvice    $advice
     * @param BetterReflectionClass $reflection
     *
     * @return InterceptionAdvice[]
     */
    private function matchInterceptionAdvice(
        InterceptionAdvice $advice,
        BetterReflectionClass $reflection
    ): array {
        static $interceptionMatcher;

        if (!isset($interceptionMatcher)) {
            $interceptionMatcher = DI::get(InterceptionMatcher::class);
        }

        return $interceptionMatcher->match($advice, $reflection);
    }
}
