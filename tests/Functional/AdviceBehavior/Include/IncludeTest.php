<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\Include;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceBehavior\Include\Aspect\DatabaseModifierAspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\Include\Target\SecureDatabaseService;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class IncludeTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see DatabaseModifierAspect::modifyData()
     */
    public function testIncludeFile(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(SecureDatabaseService::class);

        $service = new SecureDatabaseService();
        $service->load();

        $data = $service->getData();

        $this->assertEquals(
            [
                'd' => 4,
                'e' => 5,
                'f' => 6,
            ],
            $data,
        );
    }
}
