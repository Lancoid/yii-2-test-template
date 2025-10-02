<?php

declare(strict_types=1);

use app\modules\core\CoreModule;
use app\modules\site\SiteModule;
use app\modules\track\TrackModule;
use app\modules\user\UserModule;

return [
    'core' => [
        'class' => CoreModule::class,
    ],
    'site' => [
        'class' => SiteModule::class,
    ],
    'user' => [
        'class' => UserModule::class,
    ],
    'track' => [
        'class' => TrackModule::class,
    ],
];
