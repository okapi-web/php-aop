<?php

namespace Okapi\Aop\Tests\Functional\ExceptionInsideAdvice\ClassesToIntercept;

class CommentController
{
    public function saveComment(string $comment): void
    {
        // Save the comment to the database
    }
}
