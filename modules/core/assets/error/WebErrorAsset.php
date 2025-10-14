<?php

declare(strict_types=1);

namespace app\modules\core\assets\error;

use yii\web\AssetBundle;

class WebErrorAsset extends AssetBundle
{
    public $sourcePath = '@modules/site/assets/error/static';

    public $css = [
        'css/error.css',
    ];
}
