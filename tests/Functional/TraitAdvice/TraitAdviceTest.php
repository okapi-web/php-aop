<?php

namespace Okapi\Aop\Tests\Functional\TraitAdvice;

use Okapi\Aop\Tests\Functional\TraitAdvice\Aspect\RouteCachingAspect;
use Okapi\Aop\Tests\Functional\TraitAdvice\ClassesToIntercept\Router;
use Okapi\Aop\Tests\Stubs\Kernel\ApplicationKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class TraitAdviceTest extends TestCase
{
    /**
     * @see RouteCachingAspect::cacheRoutes()
     */
    public function testTraitAdvice(): void
    {
        Util::clearCache();
        ApplicationKernel::init();

        $router = new Router();

        $routes = $router->getRoutes();
        $this->assertCount(1, $routes);

        $cachedRoutes = RouteCachingAspect::$cachedRoutes;
        $this->assertCount(1, $cachedRoutes);
    }
}
