<?php

declare(strict_types=1);

namespace app\modules\site\assets\error;

use yii\web\AssetBundle;

class WebErrorAsset extends AssetBundle
{
    public $sourcePath = '@modules/site/assets/error/static';

    public $css = [
        'css/error.css',
    ];
}
