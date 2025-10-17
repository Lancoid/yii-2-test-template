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
     * Returns all roles available in the system.
     */
    public function getRolesCollections(): UserRoleCollection;

    /**
     * Returns the role with the specified name.
     * Returns null if the role does not exist.
     *
     * @param string $roleName the name of the role
     */
    public function getRole(string $roleName): ?UserRoleDto;

    /**
     * Returns the role associated with the given permission.
     * Returns null if no such role exists.
     *
     * @param UserPermissionDto $userPermissionDto the permission DTO
     */
    public function getRoleByPermission(UserPermissionDto $userPermissionDto): ?UserRoleDto;

    /**
     * Returns the roles directly assigned to the user.
     * Child roles not directly assigned will not be returned.
     *
     * @param int $userId the user ID
     */
    public function getRolesByUser(int $userId): ?UserRoleCollection;

    /**
     * Returns the child permissions of the given role.
     *
     * @param UserRoleDto $userRoleDto the role DTO
     */
    public function getRoleChildren(UserRoleDto $userRoleDto): UserPermissionCollection;

    /**
     * Checks whether the user has the specified role.
     *
     * @param UserRoleDto $userRoleDto the role DTO
     * @param int $userId the user ID
     */
    public function hasUserRole(UserRoleDto $userRoleDto, int $userId): bool;

    /**
     * Assigns a role to a user.
     *
     * @param UserRoleDto $userRoleDto the role DTO
     * @param int $userId the user ID
     *
     * @throws Exception if the role is already assigned to the user
     */
    public function assignRole(UserRoleDto $userRoleDto, int $userId): UserAssignmentDto;

    /**
     * Revokes a role from a user.
     *
     * @param UserRoleDto $userRoleDto the role DTO
     * @param int $userId the user ID
     */
    public function revokeRole(UserRoleDto $userRoleDto, int $userId): bool;

    /**
     * Returns all permissions available in the system.
     */
    public function getPermissionsCollections(): UserPermissionCollection;

    /**
     * Returns the permission with the specified name.
     * Returns null if the permission does not exist.
     *
     * @param string $permissionName the name of the permission
     */
    public function getPermission(string $permissionName): ?UserPermissionDto;

    /**
     * Returns all permissions associated with the specified role.
     *
     * @param string $roleName the name of the role
     */
    public function getPermissionsByRole(string $roleName): ?UserPermissionCollection;

    /**
     * Returns all permissions assigned to the user.
     *
     * @param int $userId the user ID
     */
    public function getPermissionsByUser(int $userId): ?UserPermissionCollection;

    /**
     * Checks if the user has all permissions of the given role.
     *
     * @param UserPermissionDto $userPermissionDto the permission DTO
     * @param int $userId the user ID
     */
    public function hasUserAllPermissionOfRole(UserPermissionDto $userPermissionDto, int $userId): bool;

    /**
     * Revokes all permissions from the user for the specified role.
     *
     * @param UserRoleDto $userRoleDto the role DTO
     * @param int $userId the user ID
     */
    public function revokeAllUserPermissionsByRole(UserRoleDto $userRoleDto, int $userId): void;

    /**
     * Checks whether the user has the specified permission.
     *
     * @param UserPermissionDto $userPermissionDto the permission DTO
     * @param int $userId the user ID
     */
    public function hasUserPermission(UserPermissionDto $userPermissionDto, int $userId): bool;

    /**
     * Assigns a permission to a user.
     *
     * @param UserPermissionDto $userPermissionDto the permission DTO
     * @param int $userId the user ID
     *
     * @throws Exception if the permission is already assigned to the user
     */
    public function assignPermission(UserPermissionDto $userPermissionDto, int $userId): UserAssignmentDto;

    /**
     * Revokes a permission from a user.
     *
     * @param UserPermissionDto $userPermissionDto the permission DTO
     * @param int $userId the user ID
     */
    public function revokePermission(UserPermissionDto $userPermissionDto, int $userId): bool;

    /**
     * Checks if the user can perform the operation specified by the given permission name.
     * Note: The "authManager" application component must be configured for this method to work.
     * Otherwise, it will always return false.
     *
     * @param string $accessName the name of the access permission
     */
    public function can(string $accessName): bool;
}
