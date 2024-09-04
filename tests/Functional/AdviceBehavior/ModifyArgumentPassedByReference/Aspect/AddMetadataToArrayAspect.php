<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ModifyArgumentPassedByReference\Aspect;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Tests\Functional\AdviceBehavior\ModifyArgumentPassedByReference\Target\ArrayCreator;

#[Aspect]
class AddMetadataToArrayAspect
{
    #[After(
        class: ArrayCreator::class,
        method: 'createArray',
    )]
    public function addMetadata(AfterMethodInvocation $invocation): void
    {
        /** @var array $array */
        $array = $invocation->getArgument('data');
        $array['metadata'] = 'metadata';
        $invocation->setArgument('data', $array);
    }
}
