<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

/**
 * Concrete collection of UserPermissionDto items with iterator support.
 */
class UserPermissionCollection implements UserPermissionCollectionInterface
{
    /**
     * @var array<UserPermissionDto>
     */
    private array $dtoList = [];

    private int $count = 0;

    private int $position = 0;

    /**
     * Rewind the Iterator to the first element.
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Return the current element.
     */
    public function current(): UserPermissionDto
    {
        return $this->dtoList[$this->position];
    }

    /**
     * Return the key of the current element.
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * Move on to the next item.
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * Checks whether the current position is valid.
     */
    public function valid(): bool
    {
        return isset($this->dtoList[$this->position]);
    }

    /**
     * Add permission DTO to the collection.
     */
    public function add(UserPermissionDto $userPermissionDto): void
    {
        $this->dtoList[] = $userPermissionDto;
        ++$this->count;
    }

    /**
     * Remove permission from the collection by its position key.
     */
    public function remove(int $key): void
    {
        if (array_key_exists($key, $this->dtoList)) {
            unset($this->dtoList[$key]);
            --$this->count;
        }
    }

    /**
     * Get count of items in the collection.
     */
    public function count(): int
    {
        return $this->count;
    }

    /**
     * Check if a permission with the given name exists in the collection.
     */
    public function exist(string $permissionName): bool
    {
        return array_any($this->dtoList, fn ($dto): bool => $dto->getName() === $permissionName);
    }

    /**
     * Intersect collection with provided permission names and return matching DTOs.
     *
     * @param array<string> $permissionNameList
     *
     * @return array<int, UserPermissionDto>
     */
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
