<?php

declare(strict_types=1);

return [
    'assetsHashCallback' => static fn (string $path): string => Yii::$app->version . '.' . hash('md4', $path),
    'userAuthTimeout' => (int)(getenv('USER_AUTH_TIMEOUT') ?: 86400),
    'userSessionTimeout' => (int)(getenv('USER_SESSION_TIMEOUT') ?: 86400),
    'version' => getenv('APP_RELEASE'),
];
