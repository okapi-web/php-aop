<?php
/**
 * @noinspection PhpPropertyOnlyWrittenInspection
 * @noinspection PhpPossiblePolymorphicInvocationInspection
 */
namespace Okapi\Aop\Service\Processor;

use DI\Attribute\Inject;
use Microsoft\PhpParser\Node\Statement\ClassDeclaration;
use Okapi\Aop\Container\AdviceContainer;
use Okapi\Aop\Container\MethodAdviceContainer;
use Okapi\Aop\Service\Cache\CacheState;
use Okapi\Aop\Service\Matcher\AspectMatcher;
use Okapi\Aop\Service\Transform\WeavingClassBuilder;
use Okapi\CodeTransformer\Service\DI;
use Okapi\CodeTransformer\Service\Processor\TransformerProcessor;
use Okapi\CodeTransformer\Service\StreamFilter\Metadata;
use Okapi\CodeTransformer\Service\StreamFilter\Metadata\Code;
use Okapi\CodeTransformer\Transformer;
use Okapi\Filesystem\Filesystem;

// TODO: docs
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
        $className = $metadata->code->getNamespacedClass();

        // Match the advices & transformers
        $adviceContainers      = $this->aspectMatcher->matchAdvices($className);
        $transformers          = $this->transformerMatcher->match($className);
        $advicesAndTransformer = array_merge($adviceContainers, $transformers);

        // Process the aspects & transformers
        $weavingFile = $this->processAdvicesAndTransformers($metadata, $advicesAndTransformer);

        $originalFilePath = $metadata->uri;
        $proxyFilePath    = $this->cachePaths->getProxyCachePath($originalFilePath);
        $weavingFilePath  = $this->cachePaths->getWeavingCachePath($originalFilePath);
        $transformed      = $metadata->code->hasChanges();

        // Save the transformed code
        if ($transformed) {
            // Proxy
            Filesystem::writeFile(
                $proxyFilePath,
                $metadata->code->getNewSource(),
            );

            // Weaving
            if ($weavingFile) {
                Filesystem::writeFile(
                    $weavingFilePath,
                    $weavingFile,
                );
            }
        }

        // Update the cache state
        $fileModificationTime = $_SERVER['REQUEST_TIME'] ?? time();
        $aspectFilePaths      = $this->getAspectFilePaths($adviceContainers);
        $transformerFilePaths = $this->getTransformerFilePaths($transformers);
        $cacheState           = DI::make(CacheState::class, [
            'originalFilePath'     => $originalFilePath,
            'className'            => $className,
            'proxyFilePath'        => $transformed ? $proxyFilePath : null,
            'weavingFilePath'      => $weavingFile ? $weavingFilePath : null,
            'transformedTime'      => $fileModificationTime,
            'transformerFilePaths' => $transformerFilePaths,
            'aspectFilePaths'      => $aspectFilePaths,
        ]);
        $this->cacheStateManager->setCacheState($originalFilePath, $cacheState);
    }

    /**
     * Process the advices and transformers.
     *
     * @param Metadata                                             $metadata
     * @param (\Okapi\Aop\Container\AdviceContainer|Transformer)[] $adviceContainersAndTransformers
     *
     * @return string|null
     */
    private function processAdvicesAndTransformers(
        Metadata $metadata,
        array    $adviceContainersAndTransformers,
    ): ?string {
        // Sort the advices & parent by priority
        usort(
            $adviceContainersAndTransformers,
            function (AdviceContainer|Transformer $a, AdviceContainer|Transformer $b) {
                $orderA = $a instanceof AdviceContainer ? $a->advice->order : $a->order;
                $orderB = $b instanceof AdviceContainer ? $b->advice->order : $b->order;

                return $orderA <=> $orderB;
            },
        );

        // Process the advices & parent
        $adviceContainers = [];
        foreach ($adviceContainersAndTransformers as $adviceContainerOrTransformer) {
            // Process the aspect
            if ($adviceContainerOrTransformer instanceof AdviceContainer) {
                $adviceContainers[] = $adviceContainerOrTransformer;
                continue;
            }

            // Process the parent
            if ($adviceContainerOrTransformer instanceof Transformer) {
                $adviceContainerOrTransformer->transform($metadata->code);
            }
        }

        // Process the advices
        if ($adviceContainers) {
            // Convert the class to a proxy
            $this->convertToProxy($metadata);

            // Create the weaving file
            return $this->processAdviceContainers(
                $adviceContainers,
                $metadata->code,
            );
        }

        return null;
    }

    /**
     * Convert the class to a proxy.
     *
     * @param Metadata $metadata
     *
     * @return void
     */
    private function convertToProxy(Metadata $metadata): void
    {
        $code = $metadata->code;

        // Find the class declaration
        $node = $code->getSourceFileNode()->getFirstDescendantNode(ClassDeclaration::class);
        assert($node instanceof ClassDeclaration);

        // Get the class name and append the suffix
        $class           = $metadata->code->getClassName();
        $namespacedClass = $class . $this->cachePaths->proxiedSuffix;

        // Replace the class name
        $code->edit(
            $node->name,
            $namespacedClass,
        );

        // Append the child class
        $childClassPath = $this->cachePaths->getWeavingCachePath($metadata->uri);
        // language=PHP
        $codeToAppend = "\ninclude_once '$childClassPath';";
        $code->append($codeToAppend);
    }

    /**
     * Process the advices.
     *
     * @param \Okapi\Aop\Container\AdviceContainer[] $adviceContainers
     * @param Code                                   $code
     *
     * @return string
     */
    private function processAdviceContainers(array $adviceContainers, Code $code): string
    {
        $weavingClassBuilder = DI::make(WeavingClassBuilder::class, [
            'code' => $code,
        ]);

        foreach ($adviceContainers as $adviceContainer) {
            if ($adviceContainer instanceof MethodAdviceContainer) {
                $weavingClassBuilder->addMethodAdviceContainer($adviceContainer);
            }
        }

        return $weavingClassBuilder->build();
    }

    /**
     * Get the file paths of the given aspects.
     *
     * @param \Okapi\Aop\Container\AdviceContainer[] $adviceContainers
     *
     * @return string[]
     */
    protected function getAspectFilePaths(array $adviceContainers): array
    {
        $filePaths = array_map(
            function (AdviceContainer $advice) {
                return $advice->filePath;
            },
            $adviceContainers,
        );

        // Remove duplicates
        return array_unique($filePaths);
    }
}
