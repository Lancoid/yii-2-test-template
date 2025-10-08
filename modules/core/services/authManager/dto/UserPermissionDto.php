<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

/**
 * Data Transfer Object representing a user permission with its metadata.
 */
class UserPermissionDto
{
    /**
     * The name of the permission. This must be globally unique.
     */
    private string $name;

    /**
     * The permission description.
     */
    private string $description;

    /**
     * Name of the rule associated with this permission.
     */
    private ?string $ruleName;

    /**
     * Additional data associated with this permission.
     */
    private mixed $data;

    /**
     * UNIX timestamp representing the permission creation time.
     */
    private int $createdAt;

    /**
     * UNIX timestamp representing the permission updating time.
     */
    private int $updatedAt;

    /**
     * Whether the user has this permission.
     */
    private bool $status = false;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setRuleName(?string $ruleName): void
    {
        $this->ruleName = $ruleName;
    }

    public function getRuleName(): ?string
    {
        return $this->ruleName;
    }

    public function setData(mixed $data): void
    {
        $this->data = $data;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @param int $createdAt UNIX timestamp
     */
    public function setCreatedAt(int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return int UNIX timestamp
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @param int $updatedAt UNIX timestamp
     */
    public function setUpdatedAt(int $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int UNIX timestamp
     */
    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    /**
     * @param bool $status Whether the user has this permission
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    /**
     * @return bool Whether the user has this permission
     */
    public function getStatus(): bool
    {
        return $this->status;
    }
}
