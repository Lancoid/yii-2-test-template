<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

/**
 * Data Transfer Object representing a user's assignment to a role or permission.
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

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setAccessName(string $accessName): void
    {
        $this->accessName = $accessName;
    }

    public function getAccessName(): string
    {
        return $this->accessName;
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
}
