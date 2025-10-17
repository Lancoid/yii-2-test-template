<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

/**
 * Concrete collection of UserRoleDto items with iterator and countable support.
 *
 * Provides methods to add, remove, check existence, count, and intersect user roles.
 */
class UserRoleCollection implements UserRoleCollectionInterface
{
    /**
     * @var array<int, UserRoleDto>
     */
    private array $dtoList;

    private int $position = 0;

    /**
     * UserRoleCollection constructor.
     *
     * @param array<int, UserRoleDto> $dtoList
     */
    public function __construct(array $dtoList = [])
    {
        $this->dtoList = array_values($dtoList);
    }

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

    public function add(UserRoleDto $userRoleDto): void
    {
        $this->dtoList[] = $userRoleDto;
    }

    public function remove(int $key): void
    {
        if (array_key_exists($key, $this->dtoList)) {
            unset($this->dtoList[$key]);
            $this->dtoList = array_values($this->dtoList); // reindex
        }
    }

    public function count(): int
    {
        return count($this->dtoList);
    }

    public function exists(string $roleName): bool
    {
        foreach ($this->dtoList as $dto) {
            if ($dto->getName() === $roleName) {
                return true;
            }
        }

        return false;
    }

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
