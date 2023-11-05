<?php

namespace Okapi\Aop\Tests\Functional\Kernel\CustomDependencyInjectionHandler;

use Closure;
use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Util;

class Kernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected function dependencyInjectionHandler(): ?Closure
    {
        return function (string $className) {
            echo 'Generating aspect/transformer instance: ' . $className . PHP_EOL;

            return new $className();
        };
    }

    protected array $aspects = [
        Aspect::class,
    ];
}
