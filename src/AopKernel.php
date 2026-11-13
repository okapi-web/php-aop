<?php

/**
 * @noinspection PhpInternalEntityUsedInspection
 * @noinspection PhpPropertyOnlyWrittenInspection
 */
namespace Okapi\Aop;

use Closure;
use DI\Attribute\Inject;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Component\ComponentType;
use Okapi\Aop\Core\AutoloadInterceptor\ClassLoader;
use Okapi\Aop\Core\Cache\CachePaths;
use Okapi\Aop\Core\Cache\CacheStateFactory;
use Okapi\Aop\Core\Cache\CacheStateManager;
use Okapi\Aop\Core\Container\AspectManager;
use Okapi\Aop\Core\Container\TransformerManager;
use Okapi\Aop\Core\Options;
use Okapi\Aop\Core\Processor\AspectProcessor;
use Okapi\CodeTransformer\CodeTransformerKernel;
use Okapi\CodeTransformer\Core\AutoloadInterceptor\ClassLoader as CodeTransformerClassLoader;
use Okapi\CodeTransformer\Core\Cache\CachePaths as CodeTransformerCachePaths;
use Okapi\CodeTransformer\Core\Cache\CacheStateFactory as CodeTransformerCacheStateFactory;
use Okapi\CodeTransformer\Core\Cache\CacheStateManager as CodeTransformerCacheStateManager;
use Okapi\CodeTransformer\Core\Container\TransformerManager as CodeTransformerTransformerManager;
use Okapi\CodeTransformer\Core\DI;
use Okapi\CodeTransformer\Core\Options as CodeTransformerOptions;
use Okapi\CodeTransformer\Core\Processor\TransformerProcessor;

use function DI\decorate;

/**
 * # AOP Kernel
 *
 * The AOP Kernel is the heart of the AOP library.
 * It manages an environment for Aspect Oriented Programming.
 *
 * 1. Extend this class and define a list of aspects in the {@link $aspects}
 *    property.
 * 2. Call the {@link init()} method early in the application lifecycle.
 *
 * If you want to modify the kernel options dynamically, override the
 * {@link configureOptions()} method.
 *
 * @extends CodeTransformerKernel<Closure(class-string, ComponentType): object>
 */
abstract class AopKernel extends CodeTransformerKernel
{
    // region DI

    #[Inject]
    private AspectManager $aspectManager;

    // endregion

    // region Settings

    /**
     * The cache directory.
     * <br><b>Default:</b> ROOT_DIR/cache/aop<br>
     *
     * @var string|null
     */
    protected ?string $cacheDir = null;

    /**
     * The exclude paths. Paths/directories in this array will be excluded
     *
     * @var string[]
     */
    protected array $excludePaths = [];

    // endregion

    /**
     * List of aspects to be applied.
     *
     * Class should be annotated with #[{@link Aspect}] attribute.
     *
     * @var class-string[]
     */
    protected array $aspects = [];

    /**
     * @inheritDoc
     */
    protected static function registerDependencyInjection(): void
    {
        parent::registerDependencyInjection();

        // Overload classes for extending the functionality
        DI::set(CodeTransformerOptions::class, decorate(static fn() => DI::get(Options::class)));
        DI::set(CodeTransformerCachePaths::class, decorate(static fn() => DI::get(CachePaths::class)));
        DI::set(CodeTransformerClassLoader::class, decorate(static fn(CodeTransformerClassLoader $previous) => DI::make(ClassLoader::class, [
            'originalClassLoader' => $previous->originalClassLoader,
        ])));
        DI::set(TransformerProcessor::class, decorate(static fn() => DI::get(AspectProcessor::class)));
        DI::set(CodeTransformerCacheStateFactory::class, decorate(static fn() => DI::get(CacheStateFactory::class)));
        DI::set(CodeTransformerCacheStateManager::class, decorate(static fn() => DI::get(CacheStateManager::class)));
        DI::set(CodeTransformerTransformerManager::class, decorate(static fn() => DI::get(TransformerManager::class)));
    }

    /**
     * @inheritdoc
     */
    protected function preInit(): void
    {
        // Add the aspects
        $this->aspectManager->addAspects($this->aspects);

        parent::preInit();
    }

    /**
     * Custom dependency injection handler.
     *
     * Pass a closure that takes an aspect/transformer class name as the
     * argument and returns an aspect/transformer instance.
     *
     * @return null|Closure(class-string, ComponentType): object
     */
    protected function dependencyInjectionHandler(): ?Closure
    {
        // Override this method to configure the dependency injection handler

        return null;
    }

    protected function registerServices(): void
    {
        $this->aspectManager->registerCustomDependencyInjectionHandler($this->dependencyInjectionHandler());
        $this->aspectManager->register();

        parent::registerServices();
    }
}
