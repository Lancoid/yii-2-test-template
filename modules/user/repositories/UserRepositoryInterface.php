<?php

declare(strict_types=1);

namespace app\modules\user\repositories;

use app\modules\user\models\User;
use app\modules\user\services\dto\UserDto;

interface UserRepositoryInterface
{
    public function save(UserDto $userDto): int;

    public function findById(int $id): UserDto;

    public function existById(int $id): bool;

    public function findByAccessToken(string $accessToken): UserDto;

    public function findByEmail(string $email, ?int $notId = null): UserDto;

    public function existByEmail(string $email, ?int $notId = null): bool;

    public function fillModel(User $user, UserDto $userDto): User;

    public function fillDto(User $user): UserDto;
}
