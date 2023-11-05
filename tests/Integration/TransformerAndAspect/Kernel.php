<?php

namespace Okapi\Aop\Tests\Integration\TransformerAndAspect;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Integration\TransformerAndAspect\Aspect\FixWrongReturnValueAspect;
use Okapi\Aop\Tests\Integration\TransformerAndAspect\Transformer\FixDeprecatedFunctionTransformer;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $transformers = [
        FixDeprecatedFunctionTransformer::class,
    ];

    protected array $aspects = [
        FixWrongReturnValueAspect::class,
    ];
}
