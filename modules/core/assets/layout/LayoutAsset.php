<?php

declare(strict_types=1);

namespace app\modules\core\assets\layout;

use app\modules\core\assets\vendor\FontAwesomeAsset;
use app\modules\core\assets\vendor\JqueryMaskedInputAsset;
use app\modules\core\assets\vendor\OpenSansAsset;
use yii\bootstrap5\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;

/**
 * Asset bundle for the main layout of the web application.
 *
 * Registers CSS and third-party assets required for the application's layout styling.
 */
class LayoutAsset extends AssetBundle
{
    public $sourcePath = '@modules/core/assets/layout/static';

    public $css = [
        'css/style.css',
    ];

    public $depends = [
        BootstrapAsset::class,
        FontAwesomeAsset::class,
        JqueryMaskedInputAsset::class,
        OpenSansAsset::class,
        YiiAsset::class,
        JqueryAsset::class,
    ];
}
