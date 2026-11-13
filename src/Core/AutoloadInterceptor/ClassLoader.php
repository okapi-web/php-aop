<?php

/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Core\AutoloadInterceptor;

use DI\Attribute\Inject;
use Okapi\Aop\Core\Matcher\AspectMatcher;
use Okapi\CodeTransformer\Core\AutoloadInterceptor;
use Okapi\CodeTransformer\Core\AutoloadInterceptor\ClassLoader as CodeTransformerClassLoader;
use Okapi\CodeTransformer\Core\Options\Environment;
use Okapi\CodeTransformer\Core\StreamFilter;
use Okapi\CodeTransformer\Core\StreamFilter\FilterInjector;
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
    // region DI

    #[Inject]
    private AspectMatcher $aspectMatcher;

    // endregion

    /**
     * Find the path to the file and match and apply the aspects.
     *
     * @param string $class
     *
     * @return false|string
     */
    public function findFile($class): false|string
    {
        $namespacedClass = $class;
        $filePath = $this->originalClassLoader->findFile($namespacedClass);

        // @codeCoverageIgnoreStart
        // Not sure how to test this
        if ($filePath === false) {
            return false;
        }
        // @codeCoverageIgnoreEnd

        // Prevent infinite recursion
        if ($this->isInternal($namespacedClass)) {
            return $filePath;
        }

        $filePath = Path::resolve($filePath);
        if (!is_string($filePath)) {
            return false;
        }

        $excludePaths = $this->options->getExcludePaths();
        foreach ($excludePaths as $path) {
            $resolvedPath = Path::resolve($path);
            if (is_string($resolvedPath) && str_starts_with($filePath, $resolvedPath)) {
                return $filePath;
            }
        }

        // Query cache state
        $cacheState = $this->cacheStateManager->queryCacheState($filePath);

        // Production trusts the cache; development checks freshness.
        $useCache =
            !$this->options->isDebug()
            && $cacheState !== null
            && (
                $this->options->getEnvironment() === Environment::PRODUCTION
                || $this->options->getEnvironment() === Environment::DEVELOPMENT
                && $cacheState->isFresh()
            );
        if ($useCache) {
            $cacheFilePath = $cacheState->getFilePath();
            if ($cacheFilePath) {
                $this->classContainer->addClassContext($filePath, $namespacedClass, $cacheFilePath);
                // Preserve the original source path for debuggers.
                return $this->filterInjector->rewriteCached($filePath);
            }
            return $filePath;
        }

        // Match the aspects
        $matchedAspects = $this->aspectMatcher->matchByClassLoaderAndStore($namespacedClass);

        // Match the transformer
        $matchedTransformers = $this->transformerMatcher->matchAndStore($namespacedClass, $filePath);

        // No aspects or transformers matched
        if (!($matchedAspects || $matchedTransformers)) {
            return $filePath;
        }

        // Add the class to store the file path
        $this->classContainer->addClassContext($filePath, $namespacedClass);

        // Replace the file path with a PHP stream filter
        /** @see StreamFilter::filter() */
        return $this->filterInjector->rewrite($filePath);
    }

    /**
     * Check if the class is internal to the AOP framework.
     *
     * @param string $namespacedClass
     *
     * @return bool
     */
    protected function isInternal(string $namespacedClass): bool
    {
        return str_starts_with_any_but_not(
            $namespacedClass,
            [
                'Okapi\Aop\\',
                'Okapi\CodeTransformer\\',
                'Okapi\Path\\',
                'Okapi\Wildcards\\',
                'PhpParser\\',
                'Microsoft\PhpParser\\',
                'DI\\',
                'Roave\BetterReflection\\',
                'SebastianBergmann\\',
                'PHPUnit\\',
                'Nette\\',
            ],
            [
                'Okapi\CodeTransformer\Tests\\',
                'Okapi\Aop\AopKernel',
                'Okapi\Aop\Tests\\',
                'Nette\PhpGenerator\Factory',
                'Nette\Utils\Reflection',
            ],
        );
    }
}
