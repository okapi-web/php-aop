<?php

namespace Okapi\Aop\Tests\Stubs\Etc;

use Okapi\Singleton\Singleton;

class MailQueue
{
    use Singleton;

    private array $mails = [];

    public function addMail(string $mail): void
    {
        $this->mails[] = $mail;
    }

    public function getMails(): array
    {
        return $this->mails;
    }
}
