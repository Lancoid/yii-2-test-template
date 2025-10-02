<?php

declare(strict_types=1);

namespace app\modules\user;

use Yii;
use yii\base\Module;
use yii\i18n\PhpMessageSource;

class UserModule extends Module
{
    public $controllerNamespace = 'app\modules\user\controllers';

    public function init(): void
    {
        parent::init();

        if (!isset(Yii::$app->i18n->translations['modules/user/*'])) {
            Yii::$app->i18n->translations['modules/user/*'] = [
                'class' => PhpMessageSource::class,
                'basePath' => '@modules/user/messages',
                'fileMap' => [
                    'modules/user/messages/login_form' => 'login_form.php',
                    'modules/user/messages/registration_form' => 'registration_form.php',
                ],
            ];
        }
    }

    public static function t(string $category, string $message, array $params = [], ?string $language = null): string
    {
        return Yii::t('modules/user/messages/' . $category, $message, $params, $language);
    }
}
