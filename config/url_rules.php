<?php

declare(strict_types=1);

return [
    '' => '/site/default/index',
    '/' => '/site/default/index',
    'captcha' => '/site/default/captcha',
    'login' => '/user/authorization/login',
    'logout' => '/user/authorization/logout',
    'registration' => '/user/registration/index',
    'metrics' => '/core/metrics/summary',
    'health' => '/core/metrics/health',
    '<controller>/<action>/<id:\d+>' => '<controller>/<action>',
    '<module>/<controller>/<action:[a-z-]+>' => '<module>/<controller>/<action>',
];
