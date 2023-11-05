<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Functional\AdviceBehavior\VariadicParameters\Aspect;

use Attribute;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Attributes\Before;
use Okapi\Aop\Invocation\BeforeMethodInvocation;
use Okapi\Aop\Tests\Functional\AdviceBehavior\VariadicParameters\Target\IdHelper;

#[Aspect]
#[Attribute]
class StringPrefixerAspect
{
    #[Before(
        IdHelper::class,
        'createIds',
    )]
    public function prefixString(BeforeMethodInvocation $invocation): void
    {
        $prefix = $invocation->getArgument('prefix');
        $ids = $invocation->getArgument('ids');

        foreach ($ids as &$id) {
            $id = $prefix . '-' . $id;
        }

        $invocation->setArgument('ids', $ids);
    }
}
