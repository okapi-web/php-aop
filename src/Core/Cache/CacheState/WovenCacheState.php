<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Core\Cache\CacheState;

use DI\Attribute\Inject;
use Okapi\Aop\Core\Container\AspectManager;
use Okapi\Aop\Core\Matcher\AspectMatcher;
use Okapi\CodeTransformer\Core\Cache\CacheState;

/**
 * # Woven Cache State
 *
 * This class is used to store the cache state for woven files.
 */
class WovenCacheState extends CacheState
{
    // region DI

    #[Inject]
    private AspectManager $aspectManager;

    #[Inject]
    private AspectMatcher $aspectMatcher;

    // endregion

    public const PROXY_FILE_PATH_KEY        = 'proxyFilePath';
    public const WOVEN_FILE_PATH_KEY        = 'wovenFilePath';
    public const TRANSFORMER_FILE_PATHS_KEY = 'transformerFilePaths';
    public const ADVICE_NAMES_KEY           = 'adviceNames';
    public const ASPECT_FILE_PATHS_KEY      = 'aspectFilePaths';
    public const ASPECT_CLASS_NAMES_KEY     = 'aspectClassNames';

    public string $proxyFilePath;
    public string $wovenFilePath;
    public array $transformerFilePaths;
    public array $adviceNames;
    public array $aspectFilePaths;
    public array $aspectClassNames;

    /**
     * @inheritDoc
     */
    public function getRequiredKeys(): array
    {
        return array_merge(
            parent::getRequiredKeys(),
            [
                static::PROXY_FILE_PATH_KEY,
                static::WOVEN_FILE_PATH_KEY,
                static::TRANSFORMER_FILE_PATHS_KEY,
                static::ADVICE_NAMES_KEY,
                static::ASPECT_FILE_PATHS_KEY,
                static::ASPECT_CLASS_NAMES_KEY,
            ],
        );
    }

    /**
     * @inheritDoc
     */
    public function isFresh(): bool
    {
        if (!parent::isFresh()) {
            // @codeCoverageIgnoreStart
            return false;
            // @codeCoverageIgnoreEnd
        }

        // Check if the proxy file has been deleted
        if (!file_exists($this->proxyFilePath)) {
            // @codeCoverageIgnoreStart
            return false;
            // @codeCoverageIgnoreEnd
        }

        // Check if the woven file has been deleted
        if (!file_exists($this->wovenFilePath)) {
            // @codeCoverageIgnoreStart
            return false;
            // @codeCoverageIgnoreEnd
        }

        $transformerAndAspectFilePaths = array_merge(
            $this->transformerFilePaths,
            $this->aspectFilePaths,
        );
        foreach ($transformerAndAspectFilePaths as $filePath) {
            if (!file_exists($filePath)) {
                // @codeCoverageIgnoreStart
                return false;
                // @codeCoverageIgnoreEnd
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getFilePath(): ?string
    {
        // Register the aspects
        foreach ($this->aspectClassNames as $aspectClassName) {
            $this->aspectManager->loadAspect($aspectClassName);
        }

        // Add the cached advice containers to the aspect matcher
        $this->aspectMatcher->addMatchedAdviceContainers(
            $this->namespacedClass,
            $this->getAdviceContainers(),
        );

        return $this->proxyFilePath;
    }

    /**
     * Get the advice containers for the given advice names.
     *
     * @return array
     */
    private function getAdviceContainers(): array
    {
        return $this->aspectManager->getAdviceContainersByAdviceNames(
            $this->adviceNames,
        );
    }
}
