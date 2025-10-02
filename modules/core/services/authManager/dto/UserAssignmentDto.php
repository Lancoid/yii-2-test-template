<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

class UserAssignmentDto
{
    /**
     * @description User ID
     */
    private int $userId;

    /**
     * @description The name of the role or permission
     */
    private string $accessName;

    /**
     * @description UNIX timestamp representing the assignment creation time
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

    public function setCreatedAt(int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }
}
