<?php

declare(strict_types=1);

use app\modules\core\assets\layout\LayoutAsset;
use app\modules\site\assets\error\WebErrorAsset;
use app\modules\user\assets\registration\UserRegistrationAsset;
use yii\widgets\PjaxAsset;

Yii::setAlias('@web', '/');

return [
    'bundles' => [
        /* YII */
        PjaxAsset::class,

        /* MODULE core */
        LayoutAsset::class,

        /* MODULE site */
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
