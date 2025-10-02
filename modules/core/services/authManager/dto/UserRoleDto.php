<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

class UserRoleDto
{
    /**
     * @description The name of the role. This must be globally unique.
     */
    private string $name;

    /**
     * @description The role description
     */
    private string $description;

    /**
     * @description Name of the rule associated with this role
     */
    private ?string $ruleName;

    /**
     * @description The additional data associated with this role
     */
    private mixed $data;

    /**
     * @description UNIX timestamp representing the role creation time
     */
    private int $createdAt;

    /**
     * @description UNIX timestamp representing the role updating time
     */
    private int $updatedAt;

    /**
     * @description Have user this role
     */
    private bool $status = false;

    /**
     * @description Have user enabled all permissions ot this role
     */
    private bool $hasAllPermissionEnabled = true;

    /**
     * @description Collection of permissions of this role
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

    public function setCreatedAt(int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(int $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setHasAllPermissionEnabled(bool $hasAllPermissionEnabled): void
    {
        $this->hasAllPermissionEnabled = $hasAllPermissionEnabled;
    }

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
