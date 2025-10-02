<?php

declare(strict_types=1);

namespace app\tests\fixtures;

use app\modules\track\models\Track;
use yii\test\ActiveFixture;

class TrackFixture extends ActiveFixture
{
    public $modelClass = Track::class;
}
