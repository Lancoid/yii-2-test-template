<?php

declare(strict_types=1);

namespace app\modules\core\widgets\flashAlert;

use app\modules\core\CoreModule;
use Yii;
use yii\base\Widget;

class FlashAlert extends Widget
{
    /**
     * @var array<string, array<string, string>> flashes definition
     */
    public array $flashes = [
        'success' => [
            'class' => 'success',
            'header' => 'Success',
            'icon' => 'check',
        ],
        'info' => [
            'class' => 'info',
            'header' => 'Info',
            'icon' => 'info-circle',
        ],
        'warning' => [
            'class' => 'warning',
            'header' => 'Warning',
            'icon' => 'warning',
        ],
        'error' => [
            'class' => 'danger',
            'header' => 'Error',
            'icon' => 'ban',
        ],
    ];

    public function run(): string
    {
        $flashes = Yii::$app->session->getAllFlashes();

        if (!$flashes) {
            return '';
        }

        $content = '';

        foreach ($flashes as $flashName => $messages) {
            $flashOptions = $this->flashes[$flashName] ?? $this->flashes['info'];

            if (is_string($messages)) {
                $messages = [$messages];
            }

            $alertClass = 'alert-' . $flashOptions['class'];
            $icon = $flashOptions['icon'];
            $header = $flashOptions['header'];

            $content .= $this->render('default', [
                'messages' => $messages,
                'alertClass' => $alertClass,
                'icon' => $icon,
                'header' => CoreModule::t('widgets', $header),
            ]);
        }

        return $content;
    }
}
