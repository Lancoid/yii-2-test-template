<?php

declare(strict_types=1);

namespace app\modules\user\assets\registration;

use app\modules\core\assets\vendor\JqueryMaskedInputAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * UserRegistrationAsset bundles JavaScript and dependencies for the user registration page.
 *
 * Includes input masking and jQuery support.
 */
class UserRegistrationAsset extends AssetBundle
{
    public $sourcePath = '@modules/user/assets/registration/static';

    public $js = [
        'js/registration.js',
    ];

    public $depends = [
        JqueryMaskedInputAsset::class,
        JqueryAsset::class,
    ];
}
