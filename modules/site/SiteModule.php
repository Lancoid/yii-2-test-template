<?php

declare(strict_types=1);

namespace app\modules\site;

use yii\base\Module;

class SiteModule extends Module
{
    public $controllerNamespace = 'app\modules\site\controllers';

    public $defaultRoute = 'default';
}
