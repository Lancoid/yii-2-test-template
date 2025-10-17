<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

use Iterator;

/**
 * Interface for a collection of UserRoleDto objects.
 *
 * Provides methods to add, remove, count, and intersect user roles.
 *
 * @extends Iterator<int, UserRoleDto>
 */
interface UserRoleCollectionInterface extends Iterator
{
    /**
     * Adds a UserRoleDto to the collection.
     */
    public function add(UserRoleDto $userRoleDto): void;

    /**
     * Removes a UserRoleDto from the collection by its position key.
     */
    public function remove(int $key): void;

    /**
     * Gets the count of items in the collection.
     */
    public function count(): int;

    /**
     * Intersects the collection with provided role names and returns matching DTOs.
     *
     * @param array<string> $roleNameList
     *
     * @return array<int, UserRoleDto>
     */
    public function intersect(array $roleNameList): array;
}
