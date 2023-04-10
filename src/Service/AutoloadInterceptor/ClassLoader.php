<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Service\AutoloadInterceptor;

use Composer\Autoload\ClassLoader as ComposerClassLoader;
use DI\Attribute\Inject;
use Okapi\Aop\Service\Matcher\AspectMatcher;
use Okapi\Aop\Util\ReflectionHelper;
use Okapi\CodeTransformer\Service\ClassLoader\ClassLoader as CodeTransformerClassLoader;
use Okapi\CodeTransformer\Service\DI;
use Okapi\CodeTransformer\Service\StreamFilter;
use Okapi\CodeTransformer\Service\StreamFilter\FilterInjector;
use Okapi\Path\Path;

/**
 * # AOP Class Loader
 *
 * This class loader is responsible for loading classes that should be
 * intercepted by the AOP framework.
 *
 * @see AutoloadInterceptor::overloadComposerLoaders() - Initialization of the AOP class loader.
 * @see FilterInjector::rewrite() - Switching the original file with a PHP filter.
 * @see StreamFilter::filter() - Applying the aspects to the file.
 */
class ClassLoader extends CodeTransformerClassLoader
{
    #[Inject]
    private AspectMatcher $aspectMatcher;

    /**
     * ClassLoader constructor.
     *
     * @param ComposerClassLoader $original
     */
    public function __construct(
        ComposerClassLoader $original,
    ) {
        parent::__construct($original);

        DI::get(ReflectionHelper::class)->setClassLoader($original);
    }

    /**
     * Find the path to the file and apply the aspects.
     *
     * @param $namespacedClass
     *
     * @return false|string
     */
    public function findFile($namespacedClass): false|string
    {
        $filePath = $this->original->findFile($namespacedClass);

        // @codeCoverageIgnoreStart
        // Not sure how to test this
        if ($filePath === false) {
            return false;
        }
        // @codeCoverageIgnoreEnd

        $filePath = Path::resolve($filePath);

        // Prevent infinite recursion
        if ($this->isInternal($namespacedClass)) {
            return $filePath;
        }

        // Match the aspects by class name
        if (!$this->aspectMatcher->matchClass($namespacedClass)) {
            return $filePath;
        }

        // Query cache state
        $cacheState = $this->cacheStateManager->queryCacheState($filePath);

        // If the cache is cached and up to date
        if ($cacheState && !$this->options->isDebug() && $cacheState->isFresh()) {
            // Use the cached file if aspects have been applied
            // Or return the original file if no aspects have been applied
            return $cacheState->cachedFilePath ?? $filePath;
        }

        // Match the aspects by other criteria
        if (!$this->aspectMatcher->matchAdvices($namespacedClass)) {
            return $filePath;
        }

        // Add the class to store the file path
        $this->classContainer->addNamespacedClassPath($filePath, $namespacedClass);

        // Replace the file path with a PHP stream filter
        /** @see StreamFilter::filter() */
        return $this->filterInjector->rewrite($filePath);
    }

    /**
     * Check if the class is internal to the AOP framework.
     *
     * @param string $namespacedName
     *
     * @return bool
     */
    protected function isInternal(string $namespacedName): bool
    {
        // Code Transformer
        // if (str_starts_with($class, "Okapi\\CodeTransformer\\")
        //     && !str_starts_with($class, "Okapi\\CodeTransformer\\Tests\\")) {
        //     return true;
        // }

        // AOP
        // if (str_starts_with($class, "Okapi\\Aop\\")
        //     && !str_starts_with($class, "Okapi\\Aop\\Tests\\")) {
        //     return true;
        // }

        // Wildcards
        // if (str_starts_with($class, "Okapi\\Wildcards\\")) {
        //     return true;
        // }

        // PHP Parser
        // if (str_starts_with($class, "PhpParser\\")) {
        //     return true;
        // }

        // Microsoft PHP Parser
        // if (str_starts_with($class, "Microsoft\\PhpParser\\")) {
        //     return true;
        // }

        // DI
        // if (str_starts_with($class, "DI\\")) {
        //     return true;
        // }

        // Better Reflection
        // if (str_starts_with($class, "Roave\\BetterReflection\\")) {
        //     return true;
        // }

        // SebastianBergmann
        // if (str_starts_with($class, "SebastianBergmann\\")) {
        //     return true;
        // }

        // PHPUnit
        // if (str_starts_with($class, "PHPUnit\\")) {
        //     return true;
        // }

        return false;
    }
}
