<?php

declare(strict_types=1);

namespace app\modules\user;

use Yii;
use yii\base\Module;
use yii\i18n\PhpMessageSource;

class UserModule extends Module
{
    public $controllerNamespace = 'app\modules\user\controllers';

    public $defaultRoute = 'user';

    public function init(): void
    {
        parent::init();
        self::initTranslations();
    }

    /**
     * Translates a message in the user module.
     *
     * @param string $category message category
     * @param string $message message to translate
     * @param array $params parameters for message substitution
     * @param null|string $language target language
     *
     * @return string translated message
     */
    public static function t(string $category, string $message, array $params = [], ?string $language = null): string
    {
        self::initTranslations();

        return Yii::t('modules/user/messages/' . $category, $message, $params, $language);
    }

    /**
     * Initializes translations for the user module.
     */
    public static function initTranslations(): void
    {
        if (!isset(Yii::$app->i18n->translations['modules/user/*'])) {
            Yii::$app->i18n->translations['modules/user/*'] = [
                'class' => PhpMessageSource::class,
                'basePath' => '@modules/user/messages',
                'fileMap' => [
                    'modules/user/messages/login_form' => 'login_form.php',
                    'modules/user/messages/registration_form' => 'registration_form.php',
                    'modules/user/messages/validators' => 'validators.php',
                ],
            ];
        }
    }
}
