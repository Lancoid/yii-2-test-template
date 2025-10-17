<?php

declare(strict_types=1);

namespace app\modules\user\services\update\input;

/**
 * Interface for user update input data.
 * Provides accessors for user properties required for update operations.
 */
interface UserUpdateInputInterface
{
    /**
     * Get the user ID.
     *
     * @return null|int user identifier
     */
    public function getId(): ?int;

    /**
     * Get the username.
     *
     * @return null|string username
     */
    public function getUsername(): ?string;

    /**
     * Get the email address.
     *
     * @return null|string email address
     */
    public function getEmail(): ?string;

    /**
     * Get the phone number.
     *
     * @return null|string phone number
     */
    public function getPhone(): ?string;

    /**
     * Get the user status.
     *
     * @return null|int user status
     */
    public function getStatus(): ?int;

    /**
     * Get the user role.
     *
     * @return null|string user role
     */
    public function getRole(): ?string;
}
