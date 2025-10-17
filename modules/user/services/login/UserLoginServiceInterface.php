<?php

declare(strict_types=1);

namespace app\modules\user\services\login;

use app\modules\core\services\exceptions\ServiceFormValidationException;
use app\modules\user\services\login\input\UserLoginInputInterface;
use Throwable;
use yii\db\Exception;

/**
 * Interface for user login service.
 * Handles user authentication and session management.
 */
interface UserLoginServiceInterface
{
    /**
     * Authenticates a user based on the provided login input.
     *
     * @param UserLoginInputInterface $userLoginInput input data for user login
     *
     * @return bool true if authentication is successful, false otherwise
     *
     * @throws Throwable if an unexpected error occurs
     * @throws Exception if a database error occurs
     * @throws ServiceFormValidationException if validation fails
     */
    public function handle(UserLoginInputInterface $userLoginInput): bool;
}
