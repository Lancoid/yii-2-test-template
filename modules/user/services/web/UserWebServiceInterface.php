<?php

declare(strict_types=1);

namespace app\modules\user\services\web;

use app\modules\user\services\dto\UserDto;
use Throwable;

interface UserWebServiceInterface
{
    public function login(UserDto $userDto, int $sessionTimeout = 0): bool;

    /**
     * @throws Throwable
     */
    public function getCurrent(): ?UserDto;
}
