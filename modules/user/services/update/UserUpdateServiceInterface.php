<?php

declare(strict_types=1);

namespace app\modules\user\services\update;

use app\modules\user\services\dto\UserDto;
use app\modules\user\services\update\input\UserUpdateInputInterface;
use Throwable;
use yii\db\Exception;

interface UserUpdateServiceInterface
{
    /**
     * @throws Throwable
     * @throws Exception
     */
    public function handle(UserDto $userDto, UserUpdateInputInterface $userUpdateInput): int;
}
