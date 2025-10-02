<?php

declare(strict_types=1);

/** @noinspection PhpUnhandledExceptionInspection */

use yii\web\Application;

defined('YII_DEBUG') || define('YII_DEBUG', true);
defined('YII_ENV') || define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/_bootstrap.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

new Application($config)->run();
