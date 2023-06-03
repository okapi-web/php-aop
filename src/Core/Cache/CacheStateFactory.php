<?php

namespace Okapi\Aop\Core\Cache;

use Okapi\Aop\Core\Cache\CacheState\WovenCacheState;
use Okapi\CodeTransformer\Core\Cache\CacheStateFactory as CodeTransformerCacheStateFactory;

/**
 * @inheritDoc
 */
class CacheStateFactory extends CodeTransformerCacheStateFactory
{
    /**
     * @inheritDoc
     */
    public const CACHE_STATE_MAP = [
        ...parent::CACHE_STATE_MAP,
        'WovenCacheState' => WovenCacheState::class,
    ];
}
