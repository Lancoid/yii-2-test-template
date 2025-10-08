<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

class UserRoleCollection implements UserRoleCollectionInterface
{
    /**
     * @var UserRoleDto[]
     */
    private array $dtoList = [];

    private int $count = 0;

    private int $position = 0;

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): UserRoleDto
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

    public function add(UserRoleDto $userRoleDto): void
    {
        $this->dtoList[] = $userRoleDto;
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

    public function exist(string $roleName): bool
    {
        return array_any($this->dtoList, fn($data) => $data->getName() === $roleName);

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
