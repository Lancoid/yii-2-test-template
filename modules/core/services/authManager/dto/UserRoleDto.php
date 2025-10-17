<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

/**
 * Data Transfer Object representing a user role with its metadata and permissions.
 *
 * @property string $name Globally unique name of the role.
 * @property string $description Description of the role.
 * @property null|string $ruleName Name of the rule associated with this role.
 * @property mixed $data Additional data associated with this role.
 * @property int $createdAt UNIX timestamp of creation.
 * @property int $updatedAt UNIX timestamp of last update.
 * @property bool $status Whether the user has this role.
 * @property bool $hasAllPermissionsEnabled Whether all permissions are enabled for this role.
 * @property null|UserPermissionCollection $userPermissionCollection Collection of permissions for this role.
 */
class UserRoleDto
{
    private string $name;
    private string $description;
    private ?string $ruleName;
    private mixed $data;
    private int $createdAt;
    private int $updatedAt;
    private bool $status;
    private bool $hasAllPermissionsEnabled;
    private ?UserPermissionCollection $userPermissionCollection;

    /**
     * UserRoleDto constructor.
     */
    public function __construct(
        string $name,
        string $description,
        ?string $ruleName,
        mixed $data,
        int $createdAt,
        int $updatedAt,
        bool $status = false,
        bool $hasAllPermissionsEnabled = true,
        ?UserPermissionCollection $userPermissionCollection = null
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->ruleName = $ruleName;
        $this->data = $data;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->status = $status;
        $this->hasAllPermissionsEnabled = $hasAllPermissionsEnabled;
        $this->userPermissionCollection = $userPermissionCollection;
    }

    /**
     * Sets the name of the role.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns the name of the role.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the description of the role.
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Returns the description of the role.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the rule name associated with this role.
     */
    public function setRuleName(?string $ruleName): void
    {
        $this->ruleName = $ruleName;
    }

    /**
     * Returns the rule name associated with this role.
     */
    public function getRuleName(): ?string
    {
        return $this->ruleName;
    }

    /**
     * Sets additional data for this role.
     */
    public function setData(mixed $data): void
    {
        $this->data = $data;
    }

    /**
     * Returns additional data for this role.
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * Sets the creation timestamp.
     *
     * @param int $createdAt UNIX timestamp
     */
    public function setCreatedAt(int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Returns the creation timestamp.
     *
     * @return int UNIX timestamp
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * Sets the update timestamp.
     *
     * @param int $updatedAt UNIX timestamp
     */
    public function setUpdatedAt(int $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Returns the update timestamp.
     *
     * @return int UNIX timestamp
     */
    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    /**
     * Sets the status of the role.
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    /**
     * Returns the status of the role.
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * Sets whether all permissions are enabled for this role.
     */
    public function setHasAllPermissionsEnabled(bool $hasAllPermissionsEnabled): void
    {
        $this->hasAllPermissionsEnabled = $hasAllPermissionsEnabled;
    }

    /**
     * Returns whether all permissions are enabled for this role.
     */
    public function hasAllPermissionsEnabled(): bool
    {
        return $this->hasAllPermissionsEnabled;
    }

    /**
     * Sets the collection of permissions for this role.
     */
    public function setPermissions(?UserPermissionCollection $userPermissionCollection): void
    {
        $this->userPermissionCollection = $userPermissionCollection;
    }

    /**
     * Returns the collection of permissions for this role.
     */
    public function getPermissions(): ?UserPermissionCollection
    {
        return $this->userPermissionCollection;
    }
}
