<?php
/** @noinspection PhpUnusedParameterInspection */
namespace Okapi\Aop\Tests\Functional\AdviceBehavior\MultipleAdvicesWithSameAdviceTypeOnSameTargetMethod\Target;

class ProfileController
{
    public function uploadProfilePicture(string $filename, string $image): string
    {
        // Upload image to CDN

        return 'https://example.com/' . $filename;
    }
}
