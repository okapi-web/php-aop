<?php

namespace Okapi\Aop\Tests\Functional\ClassHierarchyOnlyInvokedOnce\Kernel;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\ClassHierarchyOnlyInvokedOnce\Aspect\ClassHierarchyOnlyInvokedOnceAspect;
use Okapi\Aop\Tests\Util;

class ClassHierarchyOnlyInvokedOnceKernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        ClassHierarchyOnlyInvokedOnceAspect::class,
    ];
}
