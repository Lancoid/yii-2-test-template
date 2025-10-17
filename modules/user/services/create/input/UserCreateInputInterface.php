<?php

declare(strict_types=1);

namespace app\modules\user\services\create\input;

/**
 * Interface for user creation input data.
 * Provides methods to access user registration fields.
 */
interface UserCreateInputInterface
{
    /**
     * Gets the username for registration.
     *
     * @return null|string username or null if not set
     */
    public function getUsername(): ?string;

    /**
     * Gets the password for registration.
     *
     * @return null|string password or null if not set
     */
    public function getPassword(): ?string;

    /**
     * Gets the email address for registration.
     *
     * @return null|string email or null if not set
     */
    public function getEmail(): ?string;

    /**
     * Gets the phone number for registration.
     *
     * @return null|string phone number or null if not set
     */
    public function getPhone(): ?string;

    /**
     * Gets the agreement to personal data processing.
     *
     * @return null|int agreement value or null if not set
     */
    public function getAgreementPersonalData(): ?int;

    /**
     * Gets the user status.
     *
     * @return null|int status value or null if not set
     */
    public function getStatus(): ?int;

    /**
     * Gets the user role.
     *
     * @return null|string role identifier or null if not set
     */
    public function getRole(): ?string;
}
