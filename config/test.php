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
]);
