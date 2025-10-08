<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

use Iterator;

/**
 * Interface for a collection of UserRoleDto objects.
 *
 * @extends Iterator<int, UserRoleDto>
 */
interface UserRoleCollectionInterface extends Iterator
{
    /**
     * Add role DTO to the collection.
     */
    public function add(UserRoleDto $userRoleDto): void;

    /**
     * Remove the role from the collection by its position key.
     */
    public function remove(int $key): void;

    /**
     * Get count of items in the collection.
     */
    public function count(): int;

    /**
     * Intersect collection with provided role names and return matching DTOs.
     *
     * @param array<string> $roleNameList
     *
     * @return array<UserRoleDto>
     */
    public function intersect(array $roleNameList): array;
}
