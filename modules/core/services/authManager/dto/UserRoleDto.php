<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

/**
 * Data Transfer Object representing a user role with its metadata and permissions.
 */
class UserRoleDto
{
    /**
     * The name of the role. This must be globally unique.
     */
    private string $name;

    /**
     * The role description.
     */
    private string $description;

    /**
     * Name of the rule associated with this role.
     */
    private ?string $ruleName;

    /**
     * Additional data associated with this role.
     */
    private mixed $data;

    /**
     * A UNIX timestamp representing the time of the role create.
     */
    private int $createdAt;

    /**
     * A UNIX timestamp representing the time of the role update.
     */
    private int $updatedAt;

    /**
     * Whether the user has this role.
     */
    private bool $status = false;

    /**
     * Whether the user has all permissions enabled for this role.
     */
    private bool $hasAllPermissionEnabled = true;

    /**
     * Collection of permissions of this role.
     */
    private ?UserPermissionCollection $userPermissionCollection = null;

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
     * @param bool $status Whether the user has this role
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    /**
     * @return bool Whether the user has this role
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $hasAllPermissionEnabled Whether all permissions are enabled
     */
    public function setHasAllPermissionEnabled(bool $hasAllPermissionEnabled): void
    {
        $this->hasAllPermissionEnabled = $hasAllPermissionEnabled;
    }

    /**
     * @return bool Whether all permissions are enabled for this role
     */
    public function isHasAllPermissionEnabled(): bool
    {
        return $this->hasAllPermissionEnabled;
    }

    public function setPermission(?UserPermissionCollection $userPermissionCollection): void
    {
        $this->userPermissionCollection = $userPermissionCollection;
    }

    public function getPermissions(): ?UserPermissionCollection
    {
        return $this->userPermissionCollection;
    }
}
