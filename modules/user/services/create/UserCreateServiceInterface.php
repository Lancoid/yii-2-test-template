<?php

declare(strict_types=1);

namespace app\modules\user\services\create;

use app\modules\user\services\create\input\UserCreateInputInterface;
use Throwable;
use yii\db\Exception;

interface UserCreateServiceInterface
{
    /**
     * @throws Throwable
     * @throws Exception
     */
    public function handle(UserCreateInputInterface $userCreateInput): int;
}
