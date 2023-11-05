<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\ExceptionInsideAdvice\Target;

class CommentController
{
    public function saveComment(string $comment): void
    {
        // Save the comment to the database
    }
}
