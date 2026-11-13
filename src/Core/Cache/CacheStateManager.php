<?php

/** @noinspection PhpPropertyOnlyWrittenInspection */
namespace Okapi\Aop\Core\Cache;

use DI\Attribute\Inject;
use Okapi\Aop\Core\Container\AspectManager;
use Okapi\CodeTransformer\Core\Cache\CacheStateManager as CodeTransformerCacheStateManager;

/**
 * @inheritDoc
 */
class CacheStateManager extends CodeTransformerCacheStateManager
{
    // Invalidate method-name-only join points, including caches of explicit aspects.
    private const ADVICE_DECLARATION_VERSION = 1;

    // region DI

    #[Inject]
    private AspectManager $aspectManager;

    // endregion

    /**
     * Get the hash of the transformers and aspects.
     *
     * @return string
     */
    protected function getHash(): string
    {
        $transformerHash = parent::getHash();

        $aspectAdviceNames = $this->aspectManager->getAspectAdviceNames();
        $aspectHash = md5(serialize([self::ADVICE_DECLARATION_VERSION, $aspectAdviceNames]));

        return $transformerHash . $aspectHash;
    }
}
