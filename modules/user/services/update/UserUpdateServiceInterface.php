<?php

declare(strict_types=1);

namespace app\modules\user\services\update;

use app\modules\user\services\dto\UserDto;
use app\modules\user\services\update\input\UserUpdateInputInterface;
use Throwable;
use yii\db\Exception;

/**
 * Interface for user update service.
 * Handles updating user data and returns the updated user ID.
 */
interface UserUpdateServiceInterface
{
    /**
     * Updates the user data based on the provided DTO and input.
     *
     * @param UserDto $userDto user data transfer object to update
     * @param UserUpdateInputInterface $userUpdateInput input data for user update
     *
     * @return int updated user ID
     *
     * @throws Throwable if an unexpected error occurs
     * @throws Exception if a database error occurs
     */
    public function handle(UserDto $userDto, UserUpdateInputInterface $userUpdateInput): int;
}
