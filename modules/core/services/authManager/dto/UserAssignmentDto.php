<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

/**
 * Data Transfer Object representing a user's assignment to a role or permission.
 *
 * @property int $userId User ID.
 * @property string $accessName The name of the role or permission.
 * @property int $createdAt UNIX timestamp representing the assignment creation time.
 */
class UserAssignmentDto
{
    /**
     * User ID.
     */
    private int $userId;

    /**
     * The name of the role or permission.
     */
    private string $accessName;

    /**
     * UNIX timestamp representing the assignment creation time.
     */
    private int $createdAt;

    /**
     * UserAssignmentDto constructor.
     *
     * @param int $userId user ID
     * @param string $accessName the name of the role or permission
     * @param int $createdAt UNIX timestamp representing the assignment creation time
     */
    public function __construct(int $userId, string $accessName, int $createdAt)
    {
        $this->userId = $userId;
        $this->accessName = $accessName;
        $this->createdAt = $createdAt;
    }

    /**
     * Sets the user ID.
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * Returns the user ID.
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Sets the access name (role or permission).
     */
    public function setAccessName(string $accessName): void
    {
        $this->accessName = $accessName;
    }

    /**
     * Returns the access name (role or permission).
     */
    public function getAccessName(): string
    {
        return $this->accessName;
    }

    /**
     * Sets the assignment creation time.
     *
     * @param int $createdAt UNIX timestamp
     */
    public function setCreatedAt(int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Returns the assignment creation time.
     *
     * @return int UNIX timestamp
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }
}
