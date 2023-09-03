<?php

namespace Okapi\Aop\Tests\Functional\VariadicParameters;

use Okapi\Aop\Tests\Stubs\Kernel\EmptyKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

/**
 * @see https://github.com/okapi-web/php-aop/issues/26
 */
#[RunTestsInSeparateProcesses]
class VariadicParametersTest extends TestCase
{
    public function testVariadicParameters(): void
    {
        Util::clearCache();

        EmptyKernel::init();

        $ids = ['id1', 'id2', 'id3'];

        $idHelper = new ClassesToIntercept\IdHelper();

        $result = $idHelper->createIds('prefix', ...$ids);

        $expectedResult = ['prefix-id1', 'prefix-id2', 'prefix-id3'];

        $this->assertSame($expectedResult, $result);
    }

    public function testVariadicParametersWithoutAop(): void
    {
        Util::clearCache();

        $ids = ['id1', 'id2', 'id3'];

        $idHelper = new ClassesToIntercept\IdHelper();

        $result = $idHelper->createIds('prefix', ...$ids);

        $expectedResult = ['id1', 'id2', 'id3'];

        $this->assertSame($expectedResult, $result);
    }
}
