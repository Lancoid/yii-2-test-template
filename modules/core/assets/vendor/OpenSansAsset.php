<?php

/** @noinspection SpellCheckingInspection */

declare(strict_types=1);

namespace app\modules\core\assets\vendor;

use yii\web\AssetBundle;

class OpenSansAsset extends AssetBundle
{
    public $sourcePath = '@bower/open-sans-fontface';

    public $css = [
        'open-sans.css',
    ];
}
