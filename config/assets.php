<?php

declare(strict_types=1);

use app\modules\core\assets\error\WebErrorAsset;
use app\modules\core\assets\layout\LayoutAsset;
use app\modules\user\assets\registration\UserRegistrationAsset;
use yii\widgets\PjaxAsset;

Yii::setAlias('@web', '/');

return [
    'bundles' => [
        /* YII */
        PjaxAsset::class,

        /* MODULE core */
        LayoutAsset::class,
        WebErrorAsset::class,

        /* MODULE user */
        UserRegistrationAsset::class,
    ],
    'assetManager' => [
        'basePath' => '@webroot/assets',
        'baseUrl' => '@web/assets',
        'hashCallback' => Yii::$app->params['assetsHashCallback'],
    ],
];
