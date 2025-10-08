<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

use Iterator;

/**
 * Interface for a collection of UserPermissionDto objects.
 *
 * @extends Iterator<int, UserPermissionDto>
 */
interface UserPermissionCollectionInterface extends Iterator
{
    /**
     * Add permission DTO to the collection.
     */
    public function add(UserPermissionDto $userPermissionDto): void;

    /**
     * Remove permission from the collection by its position key.
     */
    public function remove(int $key): void;

    /**
     * Get count of items in the collection.
     */
    public function count(): int;

    /**
     * Check if the permission with the specified name exists in the collection.
     */
    public function exist(string $permissionName): bool;

    /**
     * Intersect collection with provided permission names and return matching DTOs.
     *
     * @param array<string> $permissionNameList
     *
     * @return array<int, UserPermissionDto>
     */
    public function intersect(array $permissionNameList): array;
}
