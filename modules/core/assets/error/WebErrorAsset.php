<?php

declare(strict_types=1);

namespace app\modules\core\assets\error;

use yii\web\AssetBundle;

/**
 * Asset bundle for error pages in the web application.
 *
 * Registers CSS and other static resources required for error page styling.
 */
class WebErrorAsset extends AssetBundle
{
    public $sourcePath = '@modules/core/assets/error/static';

    public $css = [
        'css/error.css',
    ];
}
