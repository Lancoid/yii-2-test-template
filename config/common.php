<?php

declare(strict_types=1);

ini_set('serialize_precision', -1);

use app\modules\user\models\User;
use yii\caching\CacheInterface;
use yii\db\Connection;
use yii\gii\Module as GiiModule;
use yii\rbac\DbManager;
use yii\web\User as UserCore;

$params = require __DIR__ . '/params.php';

$config = [
    'version' => $params['version'],
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'id' => 'yii-test-application',
    'name' => 'YiiTest Application',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@assets' => '@app/assets',
        '@bower' => '@vendor/bower-asset',
        '@config' => '@app/config',
        '@modules' => '@app/modules',
        '@npm' => '@vendor/npm-asset',
        '@tests' => '@app/tests',
        '@webroot' => '@app/web',
    ],
    'components' => [
        'db' => Connection::class,
        'cache' => CacheInterface::class,
        'authManager' => [
            'class' => DbManager::class,
        ],
        'user' => [
            'class' => UserCore::class,
            'identityClass' => User::class,
            'enableSession' => true,
            'authTimeout' => $params['userAuthTimeout'],
            'loginUrl' => ['/login'],
        ],
        'log' => require __DIR__ . '/log.php',
    ],
    'vendorPath' => dirname(__DIR__) . '/vendor',
    'container' => require __DIR__ . '/common_containers.php',
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => GiiModule::class,
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => GiiModule::class,
        'allowedIPs' => ['*'],
        'generators' => [
            'module' => [
                'class' => 'app\gii\module\Generator',
                'templates' => [
                    'my' => '@app/gii/module/default',
                ],
            ],
            'model' => [
                'class' => 'app\gii\model\Generator',
                'templates' => [
                    'my' => '@app/gii/model/default',
                ],
            ],
        ],
    ];
}

return $config;
