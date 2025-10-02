<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

class UserPermissionCollection implements UserPermissionCollectionInterface
{
    /**
     * @var array<UserPermissionDto>
     */
    private array $dtoList = [];

    private int $count = 0;

    private int $position = 0;

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): UserPermissionDto
    {
        return $this->dtoList[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->dtoList[$this->position]);
    }

    public function add(UserPermissionDto $userPermissionDto): void
    {
        $this->dtoList[] = $userPermissionDto;
        ++$this->count;
    }

    public function remove(int $key): void
    {
        if (array_key_exists($key, $this->dtoList)) {
            unset($this->dtoList[$key]);
            --$this->count;
        }
    }

    public function count(): int
    {
        return $this->count;
    }

    public function exist(string $permissionName): bool
    {
        foreach ($this->dtoList as $dto) {
            if ($dto->getName() === $permissionName) {
                return true;
            }
        }

        return false;
    }

    public function intersect(array $permissionNameList): array
    {
        $result = [];

        foreach ($this->dtoList as $dto) {
            if (in_array($dto->getName(), $permissionNameList, true)) {
                $result[] = $dto;
            }
        }

        return $result;
    }
}
