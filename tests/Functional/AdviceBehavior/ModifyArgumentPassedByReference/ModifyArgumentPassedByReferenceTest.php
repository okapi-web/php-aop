<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ModifyArgumentPassedByReference;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ModifyArgumentPassedByReference\Target\ArrayCreator;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class ModifyArgumentPassedByReferenceTest extends TestCase
{
    use ClassLoaderMockTrait;

    public function testModifyArgumentPassedByReference(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(ArrayCreator::class);
        $idCreator = new ArrayCreator();

        $data = 'my-awesome-data';
        $idCreator->createArray($data);
        /** @var array $data */

        $this->assertIsArray($data);
        $this->assertArrayHasKey('metadata', $data);
        $this->assertEquals('metadata', $data['metadata']);
    }
}
