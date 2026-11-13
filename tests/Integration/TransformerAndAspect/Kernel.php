<?php

namespace Okapi\Aop\Tests\Integration\TransformerAndAspect;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Integration\TransformerAndAspect\Aspect\FixWrongReturnValueAspect;
use Okapi\Aop\Tests\Integration\TransformerAndAspect\Transformer\FixIncorrectFunctionTransformer;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    /** @var array<array-key, mixed> */
    protected array $transformers = [
        FixIncorrectFunctionTransformer::class,
    ];

    /** @var array<array-key, class-string> */
    protected array $aspects = [
        FixWrongReturnValueAspect::class,
    ];
}
