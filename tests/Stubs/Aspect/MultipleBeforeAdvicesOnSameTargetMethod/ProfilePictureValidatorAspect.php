<?php
/** @noinspection PhpUnused */
namespace Okapi\Aop\Tests\Stubs\Aspect\MultipleBeforeAdvicesOnSameTargetMethod;

use Exception;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Attributes\Before;
use Okapi\Aop\Invocation\BeforeMethodInvocation;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\MultipleBeforeAdvicesOnSameTargetMethod\ProfileController;

#[Aspect]
class ProfilePictureValidatorAspect
{
    /**
     * @throws Exception
     */
    #[Before(
        class: ProfileController::class,
        method: 'uploadProfilePicture',
    )]
    public function checkImageFormat(BeforeMethodInvocation $invocation): void
    {
        $image = $invocation->getArgument('image');
        $imageInfo = getimagesize($image);

        $allowedFormats = [IMAGETYPE_PNG];

        if (!$imageInfo || !in_array($imageInfo[2], $allowedFormats)) {
            throw new Exception('Invalid image format');
        }
    }

    /**
     * @throws Exception
     */
    #[Before(
        class: ProfileController::class,
        method: 'uploadProfilePicture',
    )]
    public function checkImageSize(BeforeMethodInvocation $invocation): void
    {
        $image = $invocation->getArgument('image');
        $imageSize = filesize($image);

        // 1 MB
        $maxSize = 1048576;

        if ($imageSize > $maxSize) {
            throw new Exception('Image is too big');
        }
    }
}
