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

        self::initTranslations();
    }

    public static function t(string $category, string $message, array $params = [], ?string $language = null): string
    {
        self::initTranslations();

        return Yii::t('modules/core/messages/' . $category, $message, $params, $language);
    }

    private static function initTranslations(): void
    {
        if (!isset(Yii::$app->i18n->translations['modules/core/*'])) {
            Yii::$app->i18n->translations['modules/core/*'] = [
                'class' => PhpMessageSource::class,
                'basePath' => '@modules/core/messages',
                'fileMap' => [
                    'modules/core/messages/menu' => 'menu.php',
                    'modules/core/messages/validators' => 'validators.php',
                    'modules/core/messages/widgets' => 'widgets.php',
                ],
            ];
        }
    }
}
