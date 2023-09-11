<?php

namespace Okapi\Aop\Tests\Stubs\Kernel;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Functional\AbstractMethod\Aspect\FileUploaderAspect;
use Okapi\Aop\Tests\Functional\AdviceMatchingMultipleClassesAndMethods\Aspect\DiscountAspect;
use Okapi\Aop\Tests\Functional\AdviceOptions\AdviceOrder\Aspect\ArticleModerationAspect;
use Okapi\Aop\Tests\Functional\BeforeAroundAfterAdviceOnSameAdviceMethod\Aspect\CalculatorLoggerAspect;
use Okapi\Aop\Tests\Functional\BeforeAroundAfterAdviceOnSameTargetMethod\Aspect\PaymentProcessorAspect;
use Okapi\Aop\Tests\Functional\ClassHierarchyAspect\Aspect\NotificationAspect;
use Okapi\Aop\Tests\Functional\ExceptionInsideAdvice\Aspect\CommentFilterAspect;
use Okapi\Aop\Tests\Functional\InterfaceAdvice\Aspect\UserInterfaceAspect;
use Okapi\Aop\Tests\Functional\ModifyArgument\Aspect\NumberHelperAspect;
use Okapi\Aop\Tests\Functional\MultipleBeforeAdvicesOnSameTargetMethod\Aspect\ProfilePictureValidatorAspect;
use Okapi\Aop\Tests\Functional\ProtectedAndPrivateMethods\Aspect\BankingAspect;
use Okapi\Aop\Tests\Functional\SelfType\Aspect\SalaryIncreaserAspect;
use Okapi\Aop\Tests\Util;

class ApplicationKernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        ArticleModerationAspect::class,
        BankingAspect::class,
        CalculatorLoggerAspect::class,
        CommentFilterAspect::class,
        DiscountAspect::class,
        FileUploaderAspect::class,
        NotificationAspect::class,
        NumberHelperAspect::class,
        PaymentProcessorAspect::class,
        ProfilePictureValidatorAspect::class,
        SalaryIncreaserAspect::class,
        UserInterfaceAspect::class,
    ];
}
