<?php

declare(strict_types=1);

namespace app\modules\core\widgets\flashAlert;

use app\modules\core\CoreModule;
use Yii;
use yii\base\Widget;

/**
 * Widget for rendering flash alert messages.
 * Displays all flash messages from the session using predefined types and styles.
 */
class FlashAlert extends Widget
{
    public const string TYPE_SUCCESS = 'success';
    public const string TYPE_INFO = 'info';
    public const string TYPE_WARNING = 'warning';
    public const string TYPE_ERROR = 'error';

    /**
     * Flash message types and their display options.
     *
     * @var array<string, array{class: string, header: string, icon: string}>
     */
    public array $flashes = [
        self::TYPE_SUCCESS => [
            'class' => 'success',
            'header' => 'Success',
            'icon' => 'check',
        ],
        self::TYPE_INFO => [
            'class' => 'info',
            'header' => 'Info',
            'icon' => 'info-circle',
        ],
        self::TYPE_WARNING => [
            'class' => 'warning',
            'header' => 'Warning',
            'icon' => 'warning',
        ],
        self::TYPE_ERROR => [
            'class' => 'danger',
            'header' => 'Error',
            'icon' => 'ban',
        ],
    ];

    /**
     * Renders all flash messages from the session.
     *
     * @return string HTML content with rendered flash alerts
     */
    public function run(): string
    {
        /** @var array<string, array<string>|string> $flashes */
        $flashes = Yii::$app->session->getAllFlashes();

        if (empty($flashes)) {
            return '';
        }

        $content = '';

        foreach ($flashes as $flashName => $messages) {
            $flashOptions = $this->flashes[$flashName] ?? $this->flashes[self::TYPE_INFO];

            if (!is_array($messages)) {
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
