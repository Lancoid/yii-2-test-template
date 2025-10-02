<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\dto;

use Iterator;

/**
 * @extends Iterator<int, UserRoleDto>
 */
interface UserRoleCollectionInterface extends Iterator
{
    public function add(UserRoleDto $userRoleDto): void;

    public function remove(int $key): void;

    public function count(): int;

    /**
     * @param array<string> $roleNameList
     *
     * @return array<UserRoleDto>
     */
    public function intersect(array $roleNameList): array;
}
