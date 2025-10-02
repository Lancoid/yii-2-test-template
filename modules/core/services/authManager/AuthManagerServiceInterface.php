<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager;

use app\modules\core\services\authManager\dto\UserAssignmentDto;
use app\modules\core\services\authManager\dto\UserPermissionCollection;
use app\modules\core\services\authManager\dto\UserPermissionDto;
use app\modules\core\services\authManager\dto\UserRoleCollection;
use app\modules\core\services\authManager\dto\UserRoleDto;
use Exception;

interface AuthManagerServiceInterface
{
    /**
     * @description Returns all roles in the system.
     */
    public function getRolesCollections(): UserRoleCollection;

    /**
     * @description Returns the role corresponding to the specified name.
     * Null is returned if no such role.
     *
     * @param string $roleName the role name
     */
    public function getRole(string $roleName): ?UserRoleDto;

    /**
     * @description Returns the role corresponding to the specified name.
     * Null is returned if no such role.
     */
    public function getRoleByPermission(UserPermissionDto $userPermissionDto): ?UserRoleDto;

    /**
     * @description Returns the roles that are assigned to the user via [[assign()]].
     *              Note that child roles that are not assigned directly to the user will not be returned.
     */
    public function getRolesByUser(int $userId): ?UserRoleCollection;

    /**
     * @description Returns the child permissions of roles.
     */
    public function getRoleChildren(UserRoleDto $userRoleDto): UserPermissionCollection;

    /**
     * @description Returns whether the role is assigned to the user or not.
     */
    public function hasUserRole(UserRoleDto $userRoleDto, int $userId): bool;

    /**
     * @description Assigns a role to a user.
     *
     * @throws Exception if the role has already been assigned to the user
     */
    public function assignRole(UserRoleDto $userRoleDto, int $userId): UserAssignmentDto;

    /**
     * @description  Revokes a role from a user.
     */
    public function revokeRole(UserRoleDto $userRoleDto, int $userId): bool;

    /**
     * @description Returns all permissions in the system.
     */
    public function getPermissionsCollections(): UserPermissionCollection;

    /**
     * @description Returns the permission corresponding to the specified name.
     * Null is returned if no such permission.
     */
    public function getPermission(string $permissionName): ?UserPermissionDto;

    /**
     * @description Returns all permissions that the specified role represents.
     */
    public function getPermissionsByRole(string $roleName): ?UserPermissionCollection;

    /**
     * @description Returns all permissions that the user has.
     */
    public function getPermissionsByUser(int $userId): ?UserPermissionCollection;

    /**
     * @description Check has user all permissions from a role.
     */
    public function hasUserAllPermissionOfRole(UserPermissionDto $userPermissionDto, int $userId): bool;

    /**
     * @description Revokes all permissions from a user.
     */
    public function revokeAllUserPermissionsByRole(UserRoleDto $userRoleDto, int $userId): void;

    public function hasUserPermission(UserPermissionDto $userPermissionDto, int $userId): bool;

    /**
     * @description Assigns a role to a user.
     *
     * @throws Exception if the role has already been assigned to the user
     */
    public function assignPermission(UserPermissionDto $userPermissionDto, int $userId): UserAssignmentDto;

    /**
     * @description Revokes a role from a user.
     */
    public function revokePermission(UserPermissionDto $userPermissionDto, int $userId): bool;

    /**
     * @description Checks if the user can perform the operation as specified by the given permission.
     *              Note that you must configure "authManager" application component in order to use this method.
     *              Otherwise, it will always return false.
     */
    public function can(string $accessName): bool;
}
