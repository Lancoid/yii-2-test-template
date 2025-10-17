<?php

declare(strict_types=1);

namespace app\modules\user\services\create;

use app\modules\user\services\create\input\UserCreateInputInterface;
use Throwable;
use yii\db\Exception;

/**
 * Interface for user creation service.
 * Handles the process of creating a new user.
 */
interface UserCreateServiceInterface
{
    /**
     * Creates a new user based on the provided input data.
     *
     * @param UserCreateInputInterface $userCreateInput input data for user creation
     *
     * @return int ID of the created user
     *
     * @throws Throwable if an unexpected error occurs
     * @throws Exception if a database error occurs
     */
    public function handle(UserCreateInputInterface $userCreateInput): int;
}
