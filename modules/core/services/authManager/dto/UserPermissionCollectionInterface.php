<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

use Iterator;

/**
 * Interface for a collection of UserPermissionDto objects.
 *
 * Provides methods to add, remove, check existence, count, and intersect permissions.
 *
 * @extends Iterator<int, UserPermissionDto>
 */
interface UserPermissionCollectionInterface extends Iterator
{
    /**
     * Adds a permission DTO to the collection.
     */
    public function add(UserPermissionDto $userPermissionDto): void;

    /**
     * Removes a permission from the collection by its position key.
     */
    public function remove(int $key): void;

    /**
     * Gets the count of items in the collection.
     */
    public function count(): int;

    /**
     * Checks if a permission with the specified name exists in the collection.
     */
    public function exists(string $permissionName): bool;

    /**
     * Intersects the collection with provided permission names and returns matching DTOs.
     *
     * @param array<string> $permissionNameList
     *
     * @return array<int, UserPermissionDto>
     */
    public function intersect(array $permissionNameList): array;
}
