<?php

namespace Okapi\Aop\Core;

use Okapi\Aop\AopKernel;
use Okapi\CodeTransformer\Core\Options as CodeTransformerOptions;

/**
 * # Options
 *
 * This class provides access to the options passed to the {@see AopKernel}.
 */
class Options extends CodeTransformerOptions
{
    /**
     * @inheritdoc
     */
    public string $defaultCacheDir = 'cache/aop';
}
