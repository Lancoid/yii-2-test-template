<?php

/** @noinspection SpellCheckingInspection */

declare(strict_types=1);

namespace app\modules\core\assets\vendor;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Open Sans web font.
 *
 * Registers Open Sans CSS for use in the application.
 */
class OpenSansAsset extends AssetBundle
{
    public $sourcePath = '@bower/open-sans-fontface';

    public $css = [
        'open-sans.css',
    ];
}
