<?php

declare(strict_types=1);

namespace app\modules\user\assets\registration;

use app\modules\core\assets\vendor\JqueryMaskedInputAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

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
