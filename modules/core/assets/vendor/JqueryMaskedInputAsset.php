<?php

/** @noinspection SpellCheckingInspection */

declare(strict_types=1);

namespace app\modules\core\assets\vendor;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class JqueryMaskedInputAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-maskedinput';

    public $js = [
        'dist/jquery.maskedinput.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}
