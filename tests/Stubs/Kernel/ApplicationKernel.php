<?php

namespace Okapi\Aop\Tests\Stubs\Kernel;

use Okapi\Aop\AopKernel;
use Okapi\Aop\Tests\Stubs\Aspect;
use Okapi\Aop\Tests\Util;

class ApplicationKernel extends AopKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $aspects = [
        Aspect\AbstractMethod\FileUploaderAspect::class,
        Aspect\AdviceMatchingMultipleClassesAndMethods\DiscountAspect::class,
        Aspect\AdviceOrder\ArticleModerationAspect::class,
        Aspect\BeforeAroundAfterAdviceOnSameAdviceMethod\CalculatorLoggerAspect::class,
        Aspect\BeforeAroundAfterAdviceOnSameTargetMethod\PaymentProcessorAspect::class,
        Aspect\ExceptionInsideAdvice\CommentFilterAspect::class,
        Aspect\InterfaceAdvice\UserInterfaceAspect::class,
        Aspect\ModifyArgument\NumberHelperAspect::class,
        Aspect\MultipleBeforeAdvicesOnSameTargetMethod\ProfilePictureValidatorAspect::class,
        Aspect\ProtectedAndPrivateMethods\BankingAspect::class,
        Aspect\SelfType\SalaryIncreaserAspect::class,
        Aspect\TraitAdvice\RouteCachingAspect::class,
    ];
}
