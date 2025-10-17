<?php

declare(strict_types=1);

namespace app\modules\user\services\dto;

/**
 * Data Transfer Object representing a user entity used across services.
 * Encapsulates user data for transfer between layers.
 */
class UserDto
{
    /**
     * User ID.
     */
    private ?int $id = null;

    /**
     * Timestamp of user creation.
     */
    private ?int $createdAt = null;

    /**
     * Timestamp of last update.
     */
    private ?int $updatedAt = null;

    /**
     * Username.
     */
    private ?string $username = null;

    /**
     * Hashed password.
     */
    private ?string $passwordHash = null;

    /**
     * Authentication key.
     */
    private ?string $authKey = null;

    /**
     * Access token.
     */
    private ?string $accessToken = null;

    /**
     * Email address.
     */
    private ?string $email = null;

    /**
     * Phone number.
     */
    private ?string $phone = null;

    /**
     * User status.
     */
    private ?int $status = null;

    /**
     * Agreement to personal data processing.
     */
    private ?int $agreementPersonalData = null;

    /**
     * Set user ID.
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get user ID.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set creation timestamp.
     */
    public function setCreatedAt(?int $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get creation timestamp.
     */
    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    /**
     * Set update timestamp.
     */
    public function setUpdatedAt(?int $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get update timestamp.
     */
    public function getUpdatedAt(): ?int
    {
        return $this->updatedAt;
    }

    /**
     * Set username.
     */
    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Set password hash.
     */
    public function setPasswordHash(?string $passwordHash): self
    {
        $this->passwordHash = $passwordHash;

        return $this;
    }

    /**
     * Get password hash.
     */
    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    /**
     * Set authentication key.
     */
    public function setAuthKey(?string $authKey): self
    {
        $this->authKey = $authKey;

        return $this;
    }

    /**
     * Get authentication key.
     */
    public function getAuthKey(): ?string
    {
        return $this->authKey;
    }

    /**
     * Set access token.
     */
    public function setAccessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get access token.
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * Set email address.
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email address.
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set phone number.
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone number.
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Set user status.
     */
    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get user status.
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * Set agreement to personal data processing.
     */
    public function setAgreementPersonalData(?int $agreementPersonalData): self
    {
        $this->agreementPersonalData = $agreementPersonalData;

        return $this;
    }

    /**
     * Get agreement to personal data processing.
     */
    public function getAgreementPersonalData(): ?int
    {
        return $this->agreementPersonalData;
    }

    /**
     * Get associative array of DTO properties.
     *
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return [
            'id' => $this->id,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'username' => $this->username,
            'passwordHash' => $this->passwordHash,
            'authKey' => $this->authKey,
            'accessToken' => $this->accessToken,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'agreementPersonalData' => $this->agreementPersonalData,
        ];
    }
}
