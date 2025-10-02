<?php

declare(strict_types=1);

use app\modules\core\components\logTarget\AppFileTarget;
use app\modules\core\services\sentry\SentryServiceInterface;
use yii\debug\Module;
use yii\log\FileTarget;

$params = require __DIR__ . '/params.php';

$maxFileSize = getenv('MAX_LOG_SIZE');
$maxLogFiles = getenv('MAX_LOG_FILES');
$maxLogExportInterval = (int)(getenv('MAX_LOG_EXPORT_INTERVAL') ?: 1000);

$maskVars = [
    '_SERVER.HTTP_AUTHORIZATION',
    '_SERVER.PHP_AUTH_USER',
    '_SERVER.PHP_AUTH_PW',
];

if (!YII_DEBUG) {
    $maskVars[] = '_POST.AuthorizationForm.password';
}

return [
    'traceLevel' => YII_DEBUG ? 3 : 0,
    'targets' => [
        [
            'class' => FileTarget::class,
            'levels' => ['error', 'warning'],
            'maxFileSize' => $maxFileSize,
            'maxLogFiles' => $maxLogFiles,
            'logVars' => ['_GET', '_POST'],
            'logFile' => '@runtime/logs/app.log',
            'maskVars' => $maskVars,
            'except' => [
                'yii\web\HttpException:401',
                'yii\web\HttpException:404',
                'track_api',
            ],
        ],
        [
            'class' => FileTarget::class,
            'levels' => ['error', 'warning'],
            'maxFileSize' => $maxFileSize,
            'maxLogFiles' => $maxLogFiles,
            'logVars' => ['_GET', '_POST'],
            'maskVars' => $maskVars,
            'logFile' => '@runtime/logs/auth_error.log',
            'categories' => [
                'yii\web\HttpException:401',
            ],
        ],
        [
            'class' => FileTarget::class,
            'levels' => ['error', 'warning'],
            'maxFileSize' => $maxFileSize,
            'maxLogFiles' => $maxLogFiles,
            'logVars' => ['_GET', '_POST'],
            'maskVars' => $maskVars,
            'logFile' => '@runtime/logs/not_found_error.log',
            'categories' => [
                'yii\web\HttpException:404',
            ],
        ],
        [
            'class' => SentryServiceInterface::class,
            'levels' => ['error', 'warning'],
            'except' => [
                'yii\web\HttpException:208',
                'yii\web\HttpException:401',
                'yii\web\HttpException:403',
                'yii\web\HttpException:404',
                'yii\web\HttpException:490',
                Module::class . '::checkAccess',
            ],
        ],
        [
            'class' => AppFileTarget::class,
            'levels' => ['error', 'warning', 'info'],
            'maxFileSize' => $maxFileSize,
            'maxLogFiles' => $maxLogFiles,
            'exportInterval' => $maxLogExportInterval,
            'logVars' => [],
            'logFile' => '@runtime/logs/track_api.log',
            'categories' => ['track_api'],
        ],
    ],
];
