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
     * @param class-string $namespacedClass
     *
     * @return false|string
     */
    public function findFile($namespacedClass): false|string
    {
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


        // Query cache state
        $cacheState = $this->cacheStateManager->queryCacheState($filePath);

        // When debugging, bypass the caching mechanism
        if ($this->options->isDebug()) {
            // ...
        }

        // In production mode, use the cache without checking if it is fresh
        elseif ($this->options->getEnvironment() === Environment::PRODUCTION
            && $cacheState
        ) {
            // Use the cached file if aspects have been applied
            // Or return the original file if no aspects have been applied
            return $cacheState->getFilePath() ?? $filePath;
        }

        // In development mode, check if the cache is fresh
        elseif ($this->options->getEnvironment() === Environment::DEVELOPMENT
            && $cacheState
            && $cacheState->isFresh()
        ) {
            return $cacheState->getFilePath() ?? $filePath;
        }


        // Match the aspects
        $matchedAspects = $this->aspectMatcher->matchByClassLoader(
            $namespacedClass,
        );

        // Match the transformer
        $matchedTransformers = $this->transformerMatcher->match(
            $namespacedClass,
            $filePath,
        );

        // No aspects or transformers matched
        if (!($matchedAspects || $matchedTransformers)) {
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
                'Nette\Utils\Reflection'
            ],
        );
    }
}
