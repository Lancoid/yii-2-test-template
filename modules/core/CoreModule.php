<?php

declare(strict_types=1);

namespace app\modules\core;

use Yii;
use yii\base\Module;
use yii\i18n\PhpMessageSource;

class CoreModule extends Module
{
    public $controllerNamespace = 'app\modules\core\controllers';

    public $defaultRoute = 'core';

    public function init(): void
    {
        parent::init();

        if (!isset(Yii::$app->i18n->translations['core'])) {
            Yii::$app->i18n->translations['core'] = [
                'class' => PhpMessageSource::class,
                'basePath' => '@modules/core/messages',
            ];
        }
    }
}
