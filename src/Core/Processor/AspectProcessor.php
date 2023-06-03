<?php
/**
 * @noinspection PhpPropertyOnlyWrittenInspection
 * @noinspection PhpPossiblePolymorphicInvocationInspection
 */
namespace Okapi\Aop\Core\Processor;

use DI\Attribute\Inject;
use Nette\PhpGenerator\Factory;
use Okapi\Aop\Core\Cache\CacheState\WovenCacheState;
use Okapi\Aop\Core\Container\AdviceContainer;
use Okapi\Aop\Core\Matcher\AspectMatcher;
use Okapi\Aop\Core\Transform\ProxiedClassModifier;
use Okapi\Aop\Core\Transform\WovenClassBuilder;
use Okapi\CodeTransformer\Core\Cache\CacheState;
use Okapi\CodeTransformer\Core\Cache\CacheState\NoTransformationsCacheState;
use Okapi\CodeTransformer\Core\Cache\CacheState\TransformedCacheState;
use Okapi\CodeTransformer\Core\DI;
use Okapi\CodeTransformer\Core\Processor\TransformerProcessor;
use Okapi\CodeTransformer\Core\StreamFilter\Metadata;
use Okapi\CodeTransformer\Transformer\Code;
use Okapi\Filesystem\Filesystem;

/**
 * # Aspect Processor
 *
 * This class is used to process the aspects and weave the advices into the
 * target classes.
 */
class AspectProcessor extends TransformerProcessor
{
    // region DI

    #[Inject]
    private AspectMatcher $aspectMatcher;

    // endregion

    /**
     * @inheritDoc
     *
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    public function transform(Metadata $metadata): void
    {
        $namespacedClass = $metadata->code->getNamespacedClass();

        // Get the matched transformers
        $transformerContainers = $this->transformerMatcher->getMatchedTransformerContainers($namespacedClass);

        // Match the advices
        $adviceContainers = $this->aspectMatcher->getMatchedAdviceContainers($namespacedClass);

        // Process the transformers
        if ($transformerContainers) {
            $this->processTransformers($metadata, $transformerContainers);
        }

        // Process the advices
        $wovenFile = null;
        if ($adviceContainers
            // Don't weave the factory
            && $namespacedClass !== Factory::class
        ) {
            $wovenFile = $this->processAdvices($metadata, $adviceContainers);
        }

        $originalFilePath = $metadata->uri;
        $proxyFilePath    = $this->cachePaths->getProxyCachePath($originalFilePath);
        $wovenFilePath    = $this->cachePaths->getWovenCachePath($originalFilePath);
        $transformed      = $metadata->code->hasChanges();

        // Save the transformed code
        if ($transformed) {
            // Proxy
            Filesystem::writeFile(
                $proxyFilePath,
                $metadata->code->getNewSource(),
            );

            // Weaving
            if ($wovenFile) {
                Filesystem::writeFile(
                    $wovenFilePath,
                    $wovenFile,
                );
            }
        }

        // Update the cache state
        $modificationTime = $_SERVER['REQUEST_TIME'] ?? time();
        if ($wovenFile) {
            $transformerFilePaths = $transformerContainers
                ? $this->getTransformerFilePaths($transformerContainers)
                : [];

            $aspectFilePaths = $this->getAspectFilePaths($adviceContainers);

            $adviceNames = $this->getAdviceNames($adviceContainers);

            $cacheState = DI::make(WovenCacheState::class, [
                CacheState::DATA => [
                    CacheState::ORIGINAL_FILE_PATH_KEY          => $originalFilePath,
                    CacheState::NAMESPACED_CLASS_KEY            => $namespacedClass,
                    CacheState::MODIFICATION_TIME_KEY           => $modificationTime,
                    WovenCacheState::PROXY_FILE_PATH_KEY        => $proxyFilePath,
                    WovenCacheState::WOVEN_FILE_PATH_KEY        => $wovenFilePath,
                    WovenCacheState::TRANSFORMER_FILE_PATHS_KEY => $transformerFilePaths,
                    WovenCacheState::ADVICE_NAMES_KEY           => $adviceNames,
                    WovenCacheState::ASPECT_FILE_PATHS_KEY      => $aspectFilePaths,
                ],
            ]);
        } elseif ($transformed) {
            $transformerFilePaths = $this->getTransformerFilePaths($transformerContainers);

            $cacheState = DI::make(TransformedCacheState::class, [
                CacheState::DATA => [
                    CacheState::ORIGINAL_FILE_PATH_KEY                => $originalFilePath,
                    CacheState::NAMESPACED_CLASS_KEY                  => $namespacedClass,
                    CacheState::MODIFICATION_TIME_KEY                 => $modificationTime,
                    TransformedCacheState::TRANSFORMED_FILE_PATH_KEY  => $proxyFilePath,
                    TransformedCacheState::TRANSFORMER_FILE_PATHS_KEY => $transformerFilePaths,
                ],
            ]);
        } else {
            $cacheState = DI::make(NoTransformationsCacheState::class, [
                CacheState::DATA => [
                    CacheState::ORIGINAL_FILE_PATH_KEY => $originalFilePath,
                    CacheState::NAMESPACED_CLASS_KEY   => $namespacedClass,
                    CacheState::MODIFICATION_TIME_KEY  => $modificationTime,
                ],
            ]);
        }

        $this->cacheStateManager->setCacheState($originalFilePath, $cacheState);
    }

    /**
     * Process the advices.
     *
     * @param Metadata          $metadata
     * @param AdviceContainer[] $adviceContainers
     *
     * @return string The woven code
     */
    private function processAdvices(
        Metadata $metadata,
        array    $adviceContainers,
    ): string {
        // Sort the advices by priority
        usort(
            $adviceContainers,
            function (AdviceContainer $a, AdviceContainer $b) {
                $orderA = $a->adviceAttributeInstance->order;
                $orderB = $b->adviceAttributeInstance->order;

                return $orderA <=> $orderB;
            },
        );

        $proxiedClassModifier = DI::make(ProxiedClassModifier::class, [
            'metadata' => $metadata,
        ]);

        $proxiedClassModifier->modify();

        // Create the weaving file
        return $this->processAdviceContainers(
            $adviceContainers,
            $metadata->code,
        );
    }

    /**
     * Process the advices.
     *
     * @param AdviceContainer[] $adviceContainers
     * @param Code              $code
     *
     * @return string
     */
    private function processAdviceContainers(
        array $adviceContainers,
        Code  $code,
    ): string {
        $weavingClassBuilder = DI::make(WovenClassBuilder::class, [
            'code'             => $code,
            'adviceContainers' => $adviceContainers,
        ]);

        return $weavingClassBuilder->build();
    }

    /**
     * Get the aspect file paths.
     *
     * @param AdviceContainer[] $adviceContainers
     *
     * @return array
     */
    private function getAspectFilePaths(array $adviceContainers): array
    {
        return array_unique(array_map(
            function (AdviceContainer $adviceContainer) {
                return $adviceContainer->aspectRefClass->getFileName();
            },
            $adviceContainers,
        ));
    }

    /**
     * Get the advice names.
     *
     * @param AdviceContainer[] $adviceContainers
     *
     * @return string[]
     */
    private function getAdviceNames(array $adviceContainers): array
    {
        return array_unique(array_map(
            function (AdviceContainer $adviceContainer) {
                return $adviceContainer->getName();
            },
            $adviceContainers,
        ));
    }
}
