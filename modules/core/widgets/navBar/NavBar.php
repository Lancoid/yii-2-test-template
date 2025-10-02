<?php

declare(strict_types=1);

namespace app\modules\core\widgets\navBar;

use Yii;
use yii\bootstrap5\NavBar as YiiNavBar;
use yii\i18n\PhpMessageSource;

class NavBar extends YiiNavBar
{
    public function init(): void
    {
        parent::init();

        Yii::$app->i18n->translations['menu'] = [
            'class' => PhpMessageSource::class,
            'basePath' => '@app/modules/core/messages',
            'fileMap' => [
                'menu' => 'menu.php',
            ],
        ];
    }

    public static function t(string $category, string $message, array $params = [], ?string $language = null): string
    {
        return Yii::t($category, $message, $params, $language);
    }
}
