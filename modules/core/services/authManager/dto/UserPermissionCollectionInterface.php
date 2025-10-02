<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

use Iterator;

/**
 * @extends Iterator<int, UserPermissionDto>
 */
interface UserPermissionCollectionInterface extends Iterator
{
    public function add(UserPermissionDto $userPermissionDto): void;

    public function remove(int $key): void;

    public function count(): int;

    public function exist(string $permissionName): bool;

    /**
     * @param array<string> $permissionNameList
     *
     * @return array<int, UserPermissionDto>
     */
    public function intersect(array $permissionNameList): array;
}
