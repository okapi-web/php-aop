<?php

namespace Okapi\Aop\Tests\Functional\Advice\AdviceOrder\ClassesToIntercept;

class ArticleManager
{
    public function createArticle(string $title, string $content)
    {
        // Code to create and save an article object
    }
}
