<?php
/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Service\Cache;

use DI\Attribute\Inject;
use Okapi\Aop\Service\Matcher\AspectMatcher;
use Okapi\CodeTransformer\Service\Cache\CacheState as CodeTransformerCacheState;

/**
 * @inheritDoc
 */
class CacheState extends CodeTransformerCacheState
{
    // region DI

    #[Inject]
    private AspectMatcher $aspectMatcher;

    // endregion

    /**
     * CacheState constructor.
     *
     * @param string      $originalFilePath
     * @param string      $className
     * @param string|null $proxyFilePath
     * @param string|null $weavingFilePath
     * @param int         $transformedTime
     * @param string[]    $transformerFilePaths
     * @param string[]    $aspectFilePaths
     */
    public function __construct(
        public string  $originalFilePath,
        public string  $className,
        public ?string $proxyFilePath,
        public ?string $weavingFilePath,
        public int     $transformedTime,
        public array   $transformerFilePaths,
        public array   $aspectFilePaths,
    ) {
        parent::__construct(
            $originalFilePath,
            $className,
            $proxyFilePath,
            $transformedTime,
            $transformerFilePaths,
        );
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'className'       => $this->className,
            'proxyFilePath'   => $this->proxyFilePath,
            'weavingFilePath' => $this->weavingFilePath,
            'transformedTime' => $this->transformedTime,
            'transformerFilePaths' => $this->transformerFilePaths,
            'aspectFilePaths' => $this->aspectFilePaths,
        ];
    }

    /**
     * @inheritDoc
     */
    public function isFresh(): bool
    {
        // @codeCoverageIgnoreStart
        // This should only happen if the project is misconfigured
        if ($this->checkInfiniteLoop()) {
            return false;
        }
        // @codeCoverageIgnoreEnd

        $allFiles = array_merge(
            [$this->originalFilePath],
            $this->transformerFilePaths,
            $this->aspectFilePaths,
        );

        if ($this->checkFilesModified($allFiles)) {
            return false;
        }

        if ($this->proxyFilePath) {
            $allFiles[] = $this->proxyFilePath;
        }

        if ($this->weavingFilePath) {
            $allFiles[] = $this->weavingFilePath;
        }

        if (!$this->checkFilesExist($allFiles)) {
            return false;
        }

        if (!$this->checkTransformerCount()) {
            return false;
        }

        return true;
    }

    /**
     * Check if the aspect and transformer count is the same.
     *
     * @return bool True if the count is the same.
     */
    protected function checkTransformerCount(): bool
    {
        $cachedTransformerCount = count($this->transformerFilePaths);
        $cachedAspectCount = count($this->aspectFilePaths);

        $currentTransformerCount = count(
            $this->transformerContainer->matchTransformers($this->className),
        );
        $currentAspectCount = count(
            $this->aspectMatcher->matchAdvices($this->className),
        );

        if ($cachedTransformerCount !== $currentTransformerCount) {
            return false;
        }

        if ($cachedAspectCount !== $currentAspectCount) {
            return false;
        }

        return true;
    }
}
