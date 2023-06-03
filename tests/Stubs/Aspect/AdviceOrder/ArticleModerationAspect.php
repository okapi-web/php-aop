<?php
/** @noinspection PhpUnused */
/** @noinspection PhpMissingReturnTypeInspection */
namespace Okapi\Aop\Tests\Stubs\Aspect\AdviceOrder;

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\AdviceOrder\ArticleManager;
use Okapi\Aop\Tests\Stubs\Etc\StackTrace;

#[Aspect]
class ArticleModerationAspect
{
    #[After(
        class: ArticleManager::class,
        method: 'createArticle',
        order: 1,
    )]
    public function validateContent()
    {
        $stackTrace = StackTrace::getInstance();
        $stackTrace->addTrace('validateContent');
    }

    #[After(
        class: ArticleManager::class,
        method: 'createArticle',
        order: -10,
    )]
    public function checkForSpam()
    {
        $stackTrace = StackTrace::getInstance();
        $stackTrace->addTrace('checkForSpam');
    }

    #[After(
        class: ArticleManager::class,
        method: 'createArticle',
        order: 10,
    )]
    public function ensureProperFormatting()
    {
        $stackTrace = StackTrace::getInstance();
        $stackTrace->addTrace('ensureProperFormatting');
    }
}
