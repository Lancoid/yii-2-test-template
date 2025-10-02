<?php

declare(strict_types=1);

use app\tests\fixtures\TrackFixture;
use app\tests\fixtures\UserFixture;

return [
    UserFixture::class => __DIR__ . '/data/user.php',
    TrackFixture::class => [],
];
