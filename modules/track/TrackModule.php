<?php

declare(strict_types=1);

namespace app\modules\track;

use Yii;
use yii\base\Module;
use yii\i18n\PhpMessageSource;

class TrackModule extends Module
{
    public $controllerNamespace = 'app\modules\track\controllers';

    public function init(): void
    {
        parent::init();

        if (!isset(Yii::$app->i18n->translations['modules/track/*'])) {
            Yii::$app->i18n->translations['modules/track/*'] = [
                'class' => PhpMessageSource::class,
                'basePath' => '@modules/track/messages',
                'fileMap' => [
                    'modules/track/messages/crud_form' => 'crud_form.php',
                ],
            ];
        }
    }

    public static function t(string $category, string $message, array $params = [], ?string $language = null): string
    {
        return Yii::t('modules/track/messages/' . $category, $message, $params, $language);
    }
}
