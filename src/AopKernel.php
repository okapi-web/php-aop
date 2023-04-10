<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop;

use DI\Attribute\Inject;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Container\AspectContainer;
use Okapi\Aop\Service\AutoloadInterceptor\ClassLoader;
use Okapi\Aop\Service\Cache\CachePaths;
use Okapi\Aop\Service\Cache\CacheState;
use Okapi\Aop\Service\Options;
use Okapi\Aop\Service\Processor\AspectProcessor;
use Okapi\CodeTransformer\CodeTransformerKernel;
use Okapi\CodeTransformer\Service\Cache\CachePaths as CodeTransformerCachePaths;
use Okapi\CodeTransformer\Service\Cache\CacheState as CodeTransformerCacheState;
use Okapi\CodeTransformer\Service\ClassLoader\ClassLoader as CodeTransformerClassLoader;
use Okapi\CodeTransformer\Service\DI;
use Okapi\CodeTransformer\Service\Options as CodeTransformerOptions;
use Okapi\CodeTransformer\Service\Processor\TransformerProcessor;
use Okapi\CodeTransformer\Service\TransformerContainer;
use function DI\decorate;

/**
 * # AOP Kernel
 *
 * The AOP Kernel is the heart of the AOP library.
 * It manages an environment for Aspect Oriented Programming.
 */
abstract class AopKernel extends CodeTransformerKernel
{
    /**
     * List of aspects to be applied.
     *
     * Class should be annotated with #[{@link Aspect}] attribute.
     *
     * @var class-string[]
     */
    protected array $aspects = [];

    /**
     * @inheritdoc
     * @internal
     */
    protected array $transformers = [];

    // region DI

    #[Inject]
    private AspectContainer $aspectContainer;

    // endregion

    /**
     * @inheritDoc
     */
    protected static function registerDependencyInjection(): void
    {
        parent::registerDependencyInjection();

        // Overload classes for extending the functionality
        DI::set(TransformerContainer::class, decorate(function() {
            return DI::get(AspectContainer::class);
        }));
        DI::set(CodeTransformerOptions::class, decorate(function() {
            return DI::get(Options::class);
        }));
        DI::set(CodeTransformerCachePaths::class, decorate(function() {
            return DI::get(CachePaths::class);
        }));
        DI::set(CodeTransformerCacheState::class, decorate(function() {
            return DI::get(CacheState::class);
        }));
        DI::set(
            CodeTransformerClassLoader::class,
            decorate(function (CodeTransformerClassLoader $previous) {
                return DI::make(ClassLoader::class, [
                    'original' => $previous->original,
                ]);
            })
        );
        DI::set(TransformerProcessor::class, decorate(function() {
            return DI::get(AspectProcessor::class);
        }));
    }

    /**
     * @inheritdoc
     */
    protected function preInit(): void
    {
        // Add the aspects
        $this->aspectContainer->addAspects($this->aspects);

        parent::preInit();
    }
}
