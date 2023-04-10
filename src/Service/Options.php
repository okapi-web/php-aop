<?php

namespace Okapi\Aop\Service;

use Okapi\CodeTransformer\Service\Options as CodeTransformerOptions;

/**
 * # Options
 *
 * The `Options` class provides access to the options passed to the `AopKernel`.
 */
class Options extends CodeTransformerOptions
{
    /**
     * @inheritdoc
     */
    public string $defaultCacheDir = 'cache/aop';
}
