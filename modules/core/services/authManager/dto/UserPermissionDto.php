<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

/**
 * Data Transfer Object representing a user permission with its metadata.
 *
 * @property string $name Globally unique name of the permission.
 * @property string $description Description of the permission.
 * @property null|string $ruleName Name of the rule associated with this permission.
 * @property mixed $data Additional data associated with this permission.
 * @property int $createdAt UNIX timestamp of creation.
 * @property int $updatedAt UNIX timestamp of last update.
 * @property bool $status Whether the user has this permission.
 */
class UserPermissionDto
{
    /**
     * Globally unique name of the permission.
     */
    private string $name;

    /**
     * Description of the permission.
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
     * UNIX timestamp of creation.
     */
    private int $createdAt;

    /**
     * UNIX timestamp of last update.
     */
    private int $updatedAt;

    /**
     * Whether the user has this permission.
     */
    private bool $status = false;

    /**
     * UserPermissionDto constructor.
     */
    public function __construct(
        string $name,
        string $description,
        ?string $ruleName,
        mixed $data,
        int $createdAt,
        int $updatedAt,
        bool $status = false
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->ruleName = $ruleName;
        $this->data = $data;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->status = $status;
    }

    /**
     * Sets the name of the permission.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns the name of the permission.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the description of the permission.
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Returns the description of the permission.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the rule name associated with this permission.
     */
    public function setRuleName(?string $ruleName): void
    {
        $this->ruleName = $ruleName;
    }

    /**
     * Returns the rule name associated with this permission.
     */
    public function getRuleName(): ?string
    {
        return $this->ruleName;
    }

    /**
     * Sets additional data for this permission.
     */
    public function setData(mixed $data): void
    {
        $this->data = $data;
    }

    /**
     * Returns additional data for this permission.
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
     * Sets the status of the permission.
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    /**
     * Returns the status of the permission.
     */
    public function getStatus(): bool
    {
        return $this->status;
    }
}
