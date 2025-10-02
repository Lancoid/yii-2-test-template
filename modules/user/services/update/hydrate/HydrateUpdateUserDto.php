<?php

declare(strict_types=1);

namespace app\modules\user\services\update\hydrate;

use app\modules\user\services\dto\UserDto;
use app\modules\user\services\update\input\UserUpdateInputInterface;
use DateTimeImmutable;

class HydrateUpdateUserDto
{
    public function hydrate(UserDto $userDto, UserUpdateInputInterface $userUpdateInput): UserDto
    {
        $dateTime = new DateTimeImmutable();

        return $userDto
            ->setUpdatedAt($dateTime->getTimestamp())
            ->setId($userUpdateInput->getId())
            ->setUsername($userUpdateInput->getUsername())
            ->setEmail($userUpdateInput->getEmail())
            ->setPhone($userUpdateInput->getPhone())
            ->setStatus($userUpdateInput->getStatus());
    }
}
