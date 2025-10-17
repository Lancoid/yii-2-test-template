<?php

declare(strict_types=1);

namespace app\modules\core\assets\vendor;

use yii\web\AssetBundle;

/**
 * Asset bundle for Font Awesome icon library.
 *
 * Registers Font Awesome CSS and webfonts for use in the application.
 */
class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@bower/font-awesome';

    public $css = [
        'css/all.css',
    ];

    public $publishOptions = [
        'only' => [
            'css/*',
            'webfonts/*',
        ],
    ];
}
