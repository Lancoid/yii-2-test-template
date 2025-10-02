<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

class UserPermissionDto
{
    /**
     * @description The name of the permission. This must be globally unique.
     */
    private string $name;

    /**
     * @description The permission description
     */
    private string $description;

    /**
     * @description Name of the rule associated with this permission
     */
    private ?string $ruleName;

    /**
     * @description The additional data associated with this permission
     */
    private mixed $data;

    /**
     * @description UNIX timestamp representing the permission creation time
     */
    private int $createdAt;

    /**
     * @description UNIX timestamp representing the permission updating time
     */
    private int $updatedAt;

    /**
     * @description Have user this permission
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
}
