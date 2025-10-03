<?php

declare(strict_types=1);

use app\tests\fixtures\TrackFixture;
use app\tests\fixtures\UserFixture;

return [
    TrackFixture::class => __DIR__ . '/data/track.php',
    UserFixture::class => __DIR__ . '/data/user.php',
];
