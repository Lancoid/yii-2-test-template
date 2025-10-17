<?php

declare(strict_types=1);

namespace app\modules\user\services\login\input;

/**
 * Interface for user login input data.
 * Provides methods to access login credentials and remember-me flag.
 */
interface UserLoginInputInterface
{
    /**
     * Gets the user email address.
     *
     * @return null|string user email or null if not set
     */
    public function getEmail(): ?string;

    /**
     * Gets the user password.
     *
     * @return null|string user password or null if not set
     */
    public function getPassword(): ?string;

    /**
     * Gets the remember-me flag.
     *
     * @return bool true if remember-me is enabled, false otherwise
     */
    public function getRememberMe(): bool;
}
