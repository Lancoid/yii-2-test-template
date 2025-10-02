<?php

declare(strict_types=1);

use yii\helpers\ArrayHelper;

$config = require __DIR__ . '/test.php';

$config['components']['request'] = ArrayHelper::merge($config['components']['request'], [
    'scriptFile' => dirname(__DIR__) . '/web/index-test.php',
]);

return $config;
