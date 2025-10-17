<?php

declare(strict_types=1);

namespace app\modules\user\services\update\hydrate;

use app\modules\user\services\dto\UserDto;
use app\modules\user\services\update\input\UserUpdateInputInterface;
use DateTimeImmutable;

/**
 * Service for hydrating UserDto with update input data.
 * Updates user properties and sets the updated timestamp.
 */
class HydrateUpdateUserDto
{
    /**
     * Hydrates the UserDto with data from UserUpdateInputInterface.
     * Sets updated fields and the current update timestamp.
     *
     * @param UserDto $userDto user data transfer object to update
     * @param UserUpdateInputInterface $userUpdateInput input data for user update
     *
     * @return UserDto updated user data transfer object
     */
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
