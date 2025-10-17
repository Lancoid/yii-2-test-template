<?php

declare(strict_types=1);

namespace app\modules\user\dataProviders;

use app\modules\user\services\dto\UserDto;

/**
 * Interface for user data provider.
 * Provides methods to access user data and check user existence.
 */
interface UserDataProviderInterface
{
    /**
     * Returns a UserDto by user ID.
     *
     * @param int $userId user identifier
     *
     * @return null|UserDto user DTO or null if not found
     */
    public function getOne(int $userId): ?UserDto;

    /**
     * Checks if a user exists by email.
     * Optionally excludes a user by ID.
     *
     * @param string $email user email
     * @param null|int $notId user ID to exclude from search
     *
     * @return bool true if user exists, false otherwise
     */
    public function existByEmail(string $email, ?int $notId = null): bool;
}
