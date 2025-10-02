<?php

declare(strict_types=1);

use app\modules\core\components\assets\AssetManagerReadOnly;
use yii\helpers\ArrayHelper;
use yii\web\AssetManager;
use yii\web\CacheSession;

$params = require __DIR__ . '/params.php';
$modules = require __DIR__ . '/modules.php';
$urlRules = require __DIR__ . '/url_rules.php';

return ArrayHelper::merge(
    require __DIR__ . '/common.php',
    [
        'modules' => $modules,
        'layoutPath' => '@app/modules/core/views/layouts',
        'components' => [
            'assetManager' => [
                'basePath' => __DIR__ . '/../web/assets',
                'baseUrl' => '/assets/',
                'class' => YII_ENV_PROD ? AssetManagerReadOnly::class : AssetManager::class,
                'hashCallback' => $params['assetsHashCallback'],
            ],
            'session' => [
                'class' => CacheSession::class,
                'cache' => 'cache',
                'cookieParams' => [
                    'httponly' => false,
                    'lifetime' => $params['userSessionTimeout'],
                    'secure' => false,
                ],
                'timeout' => $params['userSessionTimeout'],
                'useCookies' => true,
            ],
            'request' => [
                'cookieValidationKey' => 'Fi7_qj4Y0Nojuv7HJRjfUsdJGT2GSSs2',
            ],
            'response' => [
                'charset' => 'UTF-8',
                'on beforeSend' => function ($event): void {
                    if (YII_ENV_PROD) {
                        $event->sender->headers->add('X-Frame-Options', 'deny');
                    }
                },
            ],
            'user' => [
                'enableAutoLogin' => true,
            ],
            'errorHandler' => [
                'errorAction' => 'site/default/error',
            ],
            'urlManager' => [
                'baseUrl' => '/',
                'enablePrettyUrl' => true,
                'rules' => $urlRules,
                'showScriptName' => false,
            ],
        ],
    ]
);
