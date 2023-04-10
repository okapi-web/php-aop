<?php

namespace Okapi\Aop\Tests;

use Okapi\Filesystem\Filesystem;

class Util
{
    public const CACHE_DIR = __DIR__ . '/cache';

    public const CACHE_STATES_FILE = self::CACHE_DIR . '/cache_states.php';

    public static function clearCache(): void
    {
        Filesystem::rm(
            path:      Util::CACHE_DIR,
            recursive: true,
            force:     true,
        );
    }
}
