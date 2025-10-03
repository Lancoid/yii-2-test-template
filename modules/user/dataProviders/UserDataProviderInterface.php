<?php

declare(strict_types=1);

namespace app\modules\user\dataProviders;

use app\modules\user\services\dto\UserDto;

interface UserDataProviderInterface
{
    public function getOne(int $userId): ?UserDto;

    public function existByEmail(string $email, ?int $notId = null): bool;
}
