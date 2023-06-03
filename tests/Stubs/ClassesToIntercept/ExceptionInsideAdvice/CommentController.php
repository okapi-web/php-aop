<?php

namespace Okapi\Aop\Tests\Stubs\ClassesToIntercept\ExceptionInsideAdvice;

class CommentController
{
    public function saveComment(string $comment): void
    {
        // Save the comment to the database
    }
}
