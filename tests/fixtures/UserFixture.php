<?php

declare(strict_types=1);

namespace app\tests\fixtures;

use app\modules\user\models\User;
use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;
}
