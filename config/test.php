<?php

declare(strict_types=1);

use yii\helpers\ArrayHelper;

$defaultParams = require __DIR__ . '/web.php';

return ArrayHelper::merge($defaultParams, [
    'components' => [
        'request' => [
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
    ],
    'container' => ArrayHelper::merge(
        require __DIR__ . '/common_containers.php',
        require __DIR__ . '/test_containers.php'
    ),
]);
