<?php
/** @noinspection PhpExpressionResultUnusedInspection */
namespace Okapi\Aop\Tests\Functional\AdviceBehavior\Readonly;

use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceBehavior\Readonly\Target\ReadonlyClass;
use Okapi\Aop\Tests\Functional\AdviceBehavior\Readonly\Target\ReadonlyPromotedProperties;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class ReadonlyTest extends TestCase
{
    use ClassLoaderMockTrait;

    public function testReadonlyClass(): void
    {
        if (PHP_VERSION_ID < 80200) {
            $this->markTestSkipped('Readonly classes are supported only in PHP 8.2 and later.');
        }

        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(ReadonlyClass::class);

        new ReadonlyClass();

        $this->assertTrue(true);
    }

    public function testReadonlyPromotedProperties(): void
    {
        Util::clearCache();
        Kernel::init();

        $this->assertWillBeWoven(ReadonlyPromotedProperties::class);

        new ReadonlyPromotedProperties('Walter Woshid', 42);

        $this->assertTrue(true);
    }
}
