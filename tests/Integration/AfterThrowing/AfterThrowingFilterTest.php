<?php

namespace Okapi\Aop\Tests\Integration\AfterThrowing;

use InvalidArgumentException;
use Okapi\Aop\Attributes\AfterThrowing;
use Okapi\Wildcards\Regex;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;
use Throwable;

class AfterThrowingFilterTest extends TestCase
{
    public function testDefaultMatchesAllThrowables(): void
    {
        static::assertSame(Throwable::class, (new AfterThrowing())->exception);
    }

    public function testFilteringPreservesOtherAdviceOptions(): void
    {
        $class = new Regex('~^App\\\\Service$~');
        $method = new Regex('~^run$~');
        $advice = new AfterThrowing($class, $method, 10, false, true, RuntimeException::class);
        static::assertSame($class, $advice->class);
        static::assertSame($method, $advice->method);
        static::assertSame(10, $advice->order);
        static::assertFalse($advice->interceptTraitMethods);
        static::assertTrue($advice->onlyPublicMethods);
        static::assertSame(RuntimeException::class, $advice->exception);
    }

    /** @return list<array{string}> */
    public static function invalidFilters(): array
    {
        return [
            [''],
            ['MissingThrowableType'],
            [stdClass::class],
            ['Runtime*'],
        ];
    }

    #[DataProvider('invalidFilters')]
    public function testInvalidFilterIsRejected(string $filter): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid exception filter "' . $filter . '"');
        new AfterThrowing(exception: $filter);
    }
}
