<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\AfterThrowing;

use Error;
use LogicException;
use Okapi\Aop\Advice\AdviceType;
use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Util;
use Okapi\CodeTransformer\Core\Cache\CacheStateManager;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;
use TypeError;
use UnexpectedValueException;

#[RunTestsInSeparateProcesses]
class AfterThrowingTest extends TestCase
{
    use ClassLoaderMockTrait;

    private function initialize(): void
    {
        Util::clearCache();
        Kernel::init();
    }

    public function testSuccessSkipsAfterThrowing(): void
    {
        $this->initialize();
        static::assertSame('updated3', (new Target())->run('success', null, 'original', 1, 2));
        static::assertSame([], State::$invocations);
        static::assertSame(['target', 'after'], State::$events);
    }

    public function testExceptionIdentityOrderAndInvocationContext(): void
    {
        $this->initialize();
        $this->assertWillBeWoven(Target::class);
        $target = new Target();
        $exception = new RuntimeException('target failure');
        $this->assertThrownSame($exception, static fn() => $target->run('fail', $exception, 'original', 1, 2));
        static::assertSame(['target', 'early', 'late'], State::$events);
        static::assertCount(2, State::$invocations);
        foreach (State::$invocations as $invocation) {
            static::assertSame($exception, $invocation->getThrowable());
            static::assertSame($target, $invocation->getSubject());
            static::assertSame(Target::class, $invocation->getClassName());
            static::assertSame('run', $invocation->getMethodName());
            static::assertSame('updated', $invocation->getArgument('label'));
            static::assertSame([1, 2], $invocation->getArgument('values'));
            static::assertSame(AdviceType::AfterThrowing, $invocation->getAdviceType());
            static::assertFalse(method_exists($invocation, 'proceed'));
            static::assertFalse(method_exists($invocation, 'setResult'));
        }
    }

    public function testErrorsAlsoTriggerAdvice(): void
    {
        $this->initialize();
        $error = new Error('target error');
        $this->assertThrownSame($error, static fn() => (new Target())->run('fail', $error));
        static::assertSame($error, State::$invocations[0]->getThrowable());
        static::assertSame(['target', 'early', 'late'], State::$events);
    }

    public function testStaticMethod(): void
    {
        $this->initialize();
        $exception = new RuntimeException('static failure');
        $this->assertThrownSame($exception, static fn() => Target::failStatic($exception));
        static::assertSame(['early', 'late'], State::$events);
        static::assertNull(State::$invocations[0]->getSubject());
        static::assertSame('failStatic', State::$invocations[0]->getMethodName());
    }

    public function testNeverMethod(): void
    {
        $this->initialize();
        $exception = new RuntimeException('never failure');
        $this->assertThrownSame($exception, static fn() => (new Target())->failNever($exception));
        static::assertSame(['early', 'late'], State::$events);
    }

    public function testUnmatchedMethodDoesNotTriggerAdvice(): void
    {
        $this->initialize();
        $exception = new RuntimeException('unmatched');
        $this->assertThrownSame($exception, static fn() => (new Target())->unmatched($exception));
        static::assertSame([], State::$invocations);
    }

    public function testAroundCanRecoverAfterAfterThrowingRuns(): void
    {
        $this->initialize();
        static::assertSame('recovered', (new Target())->run('recover', new RuntimeException('target')));
        static::assertSame(['target', 'early', 'late', 'caught', 'after'], State::$events);
    }

    public function testAroundShortCircuitDoesNotTriggerAdvice(): void
    {
        $this->initialize();
        static::assertSame('short', (new Target())->run('short', new RuntimeException('unused')));
        static::assertSame(['after'], State::$events);
        static::assertSame([], State::$invocations);
    }

    public function testRepeatedTargetCallsNotifyForEachFailure(): void
    {
        $this->initialize();
        $exception = new RuntimeException('target');
        $this->assertThrownSame($exception, static fn() => (new Target())->run('retry', $exception));
        static::assertSame(['target', 'early', 'late', 'caught', 'target', 'early', 'late'], State::$events);
    }

    public function testOtherAdviceFailuresDoNotTriggerAfterThrowing(): void
    {
        $this->initialize();
        foreach (['before', 'around', 'after'] as $mode) {
            try {
                (new Target())->run($mode);
                static::fail('Expected advice failure.');
            } catch (RuntimeException $exception) {
                static::assertSame($mode, $exception->getMessage());
            }
        }
        static::assertSame([], State::$invocations);
    }

    public function testAfterThrowingFailurePropagatesWithoutRecursion(): void
    {
        $this->initialize();
        $replacement = new RuntimeException('observer');
        State::$observerFailure = $replacement;
        $this->assertThrownSame($replacement, static fn() => (new Target())->run(
            'fail',
            new RuntimeException('target'),
        ));
        static::assertSame(['target', 'early'], State::$events);
    }

    public function testExplicitClassAndMethodAspects(): void
    {
        $this->initialize();
        $exception = new RuntimeException('explicit');
        $classTarget = new ClassTarget();
        $methodTarget = new MethodTarget();
        $this->assertThrownSame($exception, static fn() => $classTarget->fail($exception));
        $this->assertThrownSame($exception, static fn() => $methodTarget->fail($exception));
        static::assertCount(2, State::$invocations);
        static::assertSame($classTarget, State::$invocations[0]->getSubject());
        static::assertSame($methodTarget, State::$invocations[1]->getSubject());
        $error = new Error('not an exception');
        $this->assertThrownSame($error, static fn() => $classTarget->fail($error));
        $this->assertThrownSame($error, static fn() => $methodTarget->fail($error));
        static::assertCount(2, State::$invocations);
    }

    /** @return array<string, array{class-string<Throwable>, list<string>}> */
    public static function filteredFailures(): array
    {
        return [
            'exact exception' => [RuntimeException::class, ['runtime', 'exception', 'throwable']],
            'exception subclass' => [UnexpectedValueException::class, ['runtime', 'exception', 'throwable']],
            'unrelated exception' => [LogicException::class, ['exception', 'throwable']],
            'exact error' => [Error::class, ['error', 'throwable']],
            'error subclass' => [TypeError::class, ['error', 'throwable']],
        ];
    }

    /**
     * @param class-string<Throwable> $type
     * @param list<string> $events
     */
    #[DataProvider('filteredFailures')]
    public function testFiltersIncludeSubclassesAndPreserveOrder(string $type, array $events): void
    {
        $this->initialize();
        $throwable = match ($type) {
            RuntimeException::class => new RuntimeException(),
            UnexpectedValueException::class => new UnexpectedValueException(),
            LogicException::class => new LogicException(),
            Error::class => new Error(),
            TypeError::class => new TypeError(),
            default => throw new LogicException('Unknown throwable fixture.'),
        };
        $this->assertThrownSame($throwable, static fn() => (new FilteredTarget())->fail($throwable));
        static::assertSame($events, State::$events);
        foreach (State::$invocations as $invocation) {
            static::assertSame($throwable, $invocation->getThrowable());
        }
    }

    public function testUnmatchedExceptionFilterStillRethrowsOriginal(): void
    {
        $this->initialize();
        $throwable = new LogicException('unmatched filter');
        $this->assertThrownSame($throwable, static fn() => (new FilteredTarget())->runtimeOnly($throwable));
        static::assertSame([], State::$events);
    }

    public function testCachedTargetRetainsAfterThrowingAdvice(): void
    {
        Util::clearCache();
        $this->warmCache(Kernel::class, [Target::class, FilteredTarget::class, RepeatedTarget::class]);
        Kernel::init();
        $this->assertAspectLoadedFromCache(Target::class);
        $this->assertAspectLoadedFromCache(FilteredTarget::class);
        $this->assertAspectLoadedFromCache(RepeatedTarget::class);
        $exception = new RuntimeException('cached');
        $this->assertThrownSame($exception, static fn() => (new Target())->run('fail', $exception));
        static::assertSame(['target', 'early', 'late'], State::$events);
        $error = new TypeError('cached error');
        $this->assertThrownSame($error, static fn() => (new FilteredTarget())->fail($error));
        static::assertSame(['target', 'early', 'late', 'error', 'throwable'], State::$events);
        $this->assertRepeatedDeclarations();
    }

    public function testCachedExplicitAspectsRetainFiltering(): void
    {
        Util::clearCache();
        $this->warmCache(ExplicitKernel::class, [ClassTarget::class, MethodTarget::class]);
        ExplicitKernel::init();
        $this->assertAspectLoadedFromCache(ClassTarget::class);
        $this->assertAspectLoadedFromCache(MethodTarget::class);
        $exception = new RuntimeException('cached explicit');
        $error = new Error('not an exception');
        $this->assertThrownSame($exception, static fn() => (new ClassTarget())->fail($exception));
        $this->assertThrownSame($exception, static fn() => (new MethodTarget())->fail($exception));
        $this->assertThrownSame($error, static fn() => (new ClassTarget())->fail($error));
        $this->assertThrownSame($error, static fn() => (new MethodTarget())->fail($error));
        static::assertCount(2, State::$invocations);
    }

    public function testLegacyExplicitCacheIsRebuilt(): void
    {
        Util::clearCache();
        $this->warmCache(ExplicitKernel::class, [ClassTarget::class]);
        /** @var mixed $cache */
        $cache = require Util::CACHE_STATES_FILE;
        static::assertIsArray($cache);
        /** @var mixed $hash */
        $hash = $cache[CacheStateManager::HASH] ?? null;
        static::assertIsString($hash);
        // Old caches hash only registered advice names; explicit-only kernels have none.
        $cache[CacheStateManager::HASH] = substr($hash, 0, 32) . md5(serialize([]));
        $contents = str_replace('::observe#0', '::observe', '<?php return ' . var_export($cache, true) . ';');
        static::assertNotFalse(file_put_contents(Util::CACHE_STATES_FILE, $contents));
        ExplicitKernel::init();
        $this->assertWillBeWoven(ClassTarget::class);
        $exception = new RuntimeException('rebuilt');
        $this->assertThrownSame($exception, static fn() => (new ClassTarget())->fail($exception));
        static::assertCount(1, State::$invocations);
    }

    /**
     * @param class-string<AopKernel> $kernel
     * @param list<class-string> $targets
     */
    private function warmCache(string $kernel, array $targets): void
    {
        $code =
            'require ' . var_export(dirname(__DIR__, 4) . '/vendor/autoload.php', true) . '; ' . $kernel . '::init();';
        foreach ($targets as $target) {
            $code .= 'new ' . $target . '();';
        }
        $pipes = [];
        $process = proc_open([PHP_BINARY, '-r', $code], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        static::assertIsResource($process);
        $stdout = $pipes[1] ?? null;
        $stderr = $pipes[2] ?? null;
        static::assertIsResource($stdout);
        static::assertIsResource($stderr);
        $output = stream_get_contents($stdout);
        $errors = stream_get_contents($stderr);
        fclose($stdout);
        fclose($stderr);
        static::assertSame(0, proc_close($process), (string) $output . (string) $errors);
    }

    public function testRepeatedDeclarationsKeepTheirMethodFiltersAndOrder(): void
    {
        $this->initialize();
        $this->assertWillBeWoven(RepeatedTarget::class);
        $this->assertRepeatedDeclarations();
    }

    private function assertRepeatedDeclarations(): void
    {
        $target = new RepeatedTarget();
        $runtime = new RuntimeException('runtime');
        $logic = new LogicException('logic');
        $this->assertFailureEvents($runtime, static fn() => $target->first($runtime), ['middle', 'scoped']);
        $this->assertFailureEvents($logic, static fn() => $target->first($logic), ['middle']);
        $this->assertFailureEvents($runtime, static fn() => $target->second($runtime), ['middle']);
        $this->assertFailureEvents($logic, static fn() => $target->second($logic), ['scoped', 'middle']);
        $this->assertFailureEvents($runtime, static fn() => $target->ordered($runtime), ['middle', 'ordered']);
        $this->assertFailureEvents($logic, static fn() => $target->ordered($logic), ['ordered', 'middle']);
    }

    /** @param list<string> $events */
    private function assertFailureEvents(Throwable $throwable, callable $call, array $events): void
    {
        State::$events = [];
        $this->assertThrownSame($throwable, $call);
        static::assertSame($events, State::$events);
    }

    private function assertThrownSame(Throwable $expected, callable $call): void
    {
        try {
            $call();
        } catch (Throwable $actual) {
            static::assertSame($expected, $actual);
            return;
        }
        static::fail('Expected the same throwable to propagate.');
    }
}
