<?php
/** @noinspection PhpUnhandledExceptionInspection */
namespace Okapi\Aop\Tests\Functional\AdviceBehavior\NewClassCreationWithProxiedClasses;

use DI;
use DI\ContainerBuilder;
use Okapi\Aop\Tests\ClassLoaderMockTrait;
use Okapi\Aop\Tests\Functional\AdviceBehavior\NewClassCreationWithProxiedClasses\Aspect\ModifyGroupPolicyAspect;
use Okapi\Aop\Tests\Functional\AdviceBehavior\NewClassCreationWithProxiedClasses\Target\GroupMemberService;
use Okapi\Aop\Tests\Functional\AdviceBehavior\NewClassCreationWithProxiedClasses\Target\GroupPolicy;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

#[RunTestsInSeparateProcesses]
class NewClassCreationWithProxiedClassesTest extends TestCase
{
    use ClassLoaderMockTrait;

    /**
     * @see ModifyGroupPolicyAspect::doNothing()
     */
    public function testAutowireDefinition(): void
    {
        Util::clearCache();
        Kernel::init();

        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions([
            GroupPolicy::class => DI\create(GroupPolicy::class),
            GroupMemberService::class => DI\autowire(),
        ]);

        $container = $containerBuilder->build();

        $service = $container->get(GroupMemberService::class);

        $this->assertInstanceOf(GroupMemberService::class, $service);
        $this->assertEquals(
            'Original Policy Details',
            $service->getPolicyDetails(),
        );
    }

    public function testManualDefinition(): void
    {
        Util::clearCache();
        Kernel::init();

        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions([
            GroupPolicy::class => DI\create(GroupPolicy::class),
            GroupMemberService::class => static function (ContainerInterface $container) {
                return new GroupMemberService(
                    $container->get(GroupPolicy::class)
                );
            }
        ]);

        $container = $containerBuilder->build();

        $service = $container->get(GroupMemberService::class);

        $this->assertInstanceOf(GroupMemberService::class, $service);
        $this->assertEquals(
            'Original Policy Details',
            $service->getPolicyDetails(),
        );
    }
}
