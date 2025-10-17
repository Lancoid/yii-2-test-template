<?php

declare(strict_types=1);

namespace app\modules\user\services\web;

use app\modules\user\services\dto\UserDto;
use Throwable;

/**
 * Interface for web user session management service.
 * Provides methods for user authentication and retrieving the current user.
 */
interface UserWebServiceInterface
{
    /**
     * Authenticates the user and starts a session.
     *
     * @param UserDto $userDto user data transfer object for authentication
     * @param int $sessionTimeout session duration in seconds (0 for default)
     *
     * @return bool true if login is successful, false otherwise
     */
    public function login(UserDto $userDto, int $sessionTimeout = 0): bool;

    /**
     * Retrieves the currently authenticated user.
     *
     * @return null|UserDto current user DTO or null if not authenticated
     *
     * @throws Throwable if an error occurs during retrieval
     */
    public function getCurrent(): ?UserDto;
}
