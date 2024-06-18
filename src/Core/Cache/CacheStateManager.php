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
        $aspectHash = md5(serialize($aspectAdviceNames));

        return $transformerHash . $aspectHash;
    }
}
