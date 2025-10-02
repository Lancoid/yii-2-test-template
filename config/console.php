<?php

declare(strict_types=1);

use yii\console\controllers\MigrateController;
use yii\helpers\ArrayHelper;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

return ArrayHelper::merge(
    require __DIR__ . '/common.php',
    [
        'controllerNamespace' => 'app\modules\core\commands',
        'controllerMap' => [
            'migrate' => [
                'class' => MigrateController::class,
                'migrationPath' => [
                    '@yii/rbac/migrations',
                    '@app/migrations',
                ],
            ],
        ],
    ]
);
