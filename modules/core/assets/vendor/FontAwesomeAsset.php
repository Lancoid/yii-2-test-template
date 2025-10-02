<?php

declare(strict_types=1);

namespace app\modules\core\assets\vendor;

use yii\web\AssetBundle;

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
        ]
    ];
}
