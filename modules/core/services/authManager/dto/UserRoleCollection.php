<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

/**
 * Concrete collection of UserRoleDto items with iterator support.
 */
class UserRoleCollection implements UserRoleCollectionInterface
{
    /**
     * @var UserRoleDto[]
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
    public function current(): UserRoleDto
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
     * Add role DTO to the collection.
     */
    public function add(UserRoleDto $userRoleDto): void
    {
        $this->dtoList[] = $userRoleDto;
        ++$this->count;
    }

    /**
     * Remove the role from the collection by its position key.
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
     * Check if a role with the given name exists in the collection.
     */
    public function exist(string $roleName): bool
    {
        return array_any($this->dtoList, fn ($data): bool => $data->getName() === $roleName);
    }

    /**
     * Intersect collection with provided role names and return matching DTOs.
     *
     * @param array<string> $roleNameList
     *
     * @return array<UserRoleDto>
     */
    public function intersect(array $roleNameList): array
    {
        $result = [];

        foreach ($this->dtoList as $dto) {
            if (in_array($dto->getName(), $roleNameList, true)) {
                $result[] = $dto;
            }
        }

        return $result;
    }
}
