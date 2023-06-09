<?php

namespace Okapi\Aop\Tests\Stubs\Kernel;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\TransformerAndAspect\Aspect\FixWrongReturnValueAspect;
use Okapi\Aop\Tests\Functional\TransformerAndAspect\Transformer\FixDeprecatedFunctionTransformer;
use Okapi\Aop\Tests\Util;

class TransformerAndAspectKernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $transformers = [
        FixDeprecatedFunctionTransformer::class,
    ];

    protected array $aspects = [
        FixWrongReturnValueAspect::class,
    ];
}
