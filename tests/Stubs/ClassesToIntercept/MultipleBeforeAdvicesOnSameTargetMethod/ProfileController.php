<?php
/** @noinspection PhpUnusedParameterInspection */
namespace Okapi\Aop\Tests\Stubs\ClassesToIntercept\MultipleBeforeAdvicesOnSameTargetMethod;

class ProfileController
{
    public function uploadProfilePicture(string $filename, string $image): string
    {
        // Upload image to CDN

        return 'https://example.com/' . $filename;
    }
}
