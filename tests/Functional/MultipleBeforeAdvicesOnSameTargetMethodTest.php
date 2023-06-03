<?php
/** @noinspection PhpUnusedLocalVariableInspection */
namespace Okapi\Aop\Tests\Functional;

use Exception;
use Okapi\Aop\Tests\Stubs\Aspect\MultipleBeforeAdvicesOnSameTargetMethod\ProfilePictureValidatorAspect;
use Okapi\Aop\Tests\Stubs\ClassesToIntercept\MultipleBeforeAdvicesOnSameTargetMethod\ProfileController;
use Okapi\Aop\Tests\Stubs\Kernel\ApplicationKernel;
use Okapi\Aop\Tests\Util;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
class MultipleBeforeAdvicesOnSameTargetMethodTest extends TestCase
{
    public const AVATAR_PATH              = __DIR__ . '/../media/avatar.png';
    public const AVATAR_HQ_PATH           = __DIR__ . '/../media/avatar-HQ.png';
    public const AVATAR_WRONG_FORMAT_PATH = __DIR__ . '/../media/avatar-wrong-format.jpg';

    /**
     * @see ProfilePictureValidatorAspect::checkImageSize()
     * @see ProfilePictureValidatorAspect::checkImageFormat()
     */
    public function testMultipleBeforeAdvicesOnSameTargetMethod(): void
    {
        Util::clearCache();
        ApplicationKernel::init();

        $profileController = new ProfileController();

        // Valid avatar
        $path = $profileController->uploadProfilePicture(
            'avatar',
            self::AVATAR_PATH,
        );

        // No exception thrown
        $this->assertTrue(true);

        $this->assertSame(
            'https://example.com/avatar',
            $path,
        );

        // Invalid avatar size
        $exceptionThrown = false;
        try {
            /** @noinspection PhpExpressionResultUnusedInspection */
            $profileController->uploadProfilePicture(
                'avatar',
                self::AVATAR_HQ_PATH,
            );
        } catch (Exception $e) {
            $exceptionThrown = true;
            $this->assertSame(
                'Image is too big',
                $e->getMessage(),
            );
        }
        $this->assertTrue($exceptionThrown);

        // Invalid avatar format
        $exceptionThrown = false;
        try {
            /** @noinspection PhpExpressionResultUnusedInspection */
            $profileController->uploadProfilePicture(
                'avatar',
                self::AVATAR_WRONG_FORMAT_PATH,
            );
        } catch (Exception $e) {
            $exceptionThrown = true;
            $this->assertSame(
                'Invalid image format',
                $e->getMessage(),
            );
        }
        $this->assertTrue($exceptionThrown);
    }
}
