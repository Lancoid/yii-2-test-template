<?php

declare(strict_types=1);

namespace app\modules\user\services\dto;

class UserDto
{
    private ?int $id = null;
    private ?int $createdAt = null;
    private ?int $updatedAt = null;
    private ?string $username = null;
    private ?string $passwordHash = null;
    private ?string $authKey = null;
    private ?string $accessToken = null;
    private ?string $email = null;
    private ?string $phone = null;
    private ?int $status = null;
    private ?int $agreementPersonalData = null;

    public function setId(?int $id): UserDto
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setCreatedAt(?int $createdAt): UserDto
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(?int $updatedAt): UserDto
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?int
    {
        return $this->updatedAt;
    }

    public function setUsername(?string $username): UserDto
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setPasswordHash(?string $passwordHash): UserDto
    {
        $this->passwordHash = $passwordHash;

        return $this;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function setAuthKey(?string $authKey): UserDto
    {
        $this->authKey = $authKey;

        return $this;
    }

    public function getAuthKey(): ?string
    {
        return $this->authKey;
    }

    public function setAccessToken(?string $accessToken): UserDto
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setEmail(?string $email): UserDto
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setPhone(?string $phone): UserDto
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setStatus(?int $status): UserDto
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setAgreementPersonalData(?int $agreementPersonalData): UserDto
    {
        $this->agreementPersonalData = $agreementPersonalData;

        return $this;
    }

    public function getAgreementPersonalData(): ?int
    {
        return $this->agreementPersonalData;
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return get_object_vars($this);
    }
}
