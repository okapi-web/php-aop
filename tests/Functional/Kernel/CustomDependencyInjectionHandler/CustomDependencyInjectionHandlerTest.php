<?php

namespace Okapi\Aop\Tests\Functional\Kernel\CustomDependencyInjectionHandler;

use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class CustomDependencyInjectionHandlerTest extends TestCase
{
    public function testCustomDependencyInjectionHandler(): void
    {
        Util::clearCache();

        ob_start();

        Kernel::init();

        $output = ob_get_clean();

        $this->assertStringContainsString(
            'Generating aspect/transformer instance: ' . Aspect::class,
            $output,
        );

        $class = new Target();

        $this->assertSame(
            420,
            $class->answer(),
        );
    }
}
