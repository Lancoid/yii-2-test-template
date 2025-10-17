<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager;

use app\modules\core\services\authManager\dto\UserAssignmentDto;
use app\modules\core\services\authManager\dto\UserPermissionCollection;
use app\modules\core\services\authManager\dto\UserPermissionDto;
use app\modules\core\services\authManager\dto\UserRoleCollection;
use app\modules\core\services\authManager\dto\UserRoleDto;
use app\modules\core\services\authManager\exceptions\UserPermissionNotFoundException;
use app\modules\core\services\authManager\exceptions\UserRoleNotFoundException;
use ArrayIterator;
use Yii;
use yii\rbac\Assignment;
use yii\rbac\Item;
use yii\rbac\ManagerInterface;
use yii\rbac\Permission;
use yii\rbac\Role;

class AuthManagerService implements AuthManagerServiceInterface
{
    private ManagerInterface $authManager;

    public function __construct(ManagerInterface $authManager)
    {
        $this->authManager = $authManager;
    }

    public function getRolesCollections(): UserRoleCollection
    {
        $roles = $this->getAllRoles();
        $userRoleCollection = new UserRoleCollection();

        foreach ($roles as $role) {
            $userRoleCollection->add($this->createRoleDto($role));
        }

        return $userRoleCollection;
    }

    public function getRole(string $roleName): ?UserRoleDto
    {
        $role = $this->authManager->getRole($roleName);

        return $role ? $this->createRoleDto($role) : null;
    }

    public function getRoleByPermission(UserPermissionDto $userPermissionDto): ?UserRoleDto
    {
        $permission = $this->authManager->getPermission($userPermissionDto->getName());
        if (!$permission) {
            return null;
        }
        foreach ($this->getAllRoles() as $role) {
            if ($this->hasChild($role, $permission)) {
                return $this->createRoleDto($role);
            }
        }

        return null;
    }

    public function getRolesByUser(int $userId): ?UserRoleCollection
    {
        $roles = $this->authManager->getRolesByUser($userId);
        if (!$roles) {
            return null;
        }
        $userRoleCollection = new UserRoleCollection();
        foreach ($roles as $role) {
            $dto = $this->createRoleDto($role);
            $dto->setStatus(true);
            $userRoleCollection->add($dto);
        }

        return $userRoleCollection;
    }

    public function getRoleChildren(UserRoleDto $userRoleDto): UserPermissionCollection
    {
        $permissions = $this->authManager->getChildren($userRoleDto->getName());
        $userPermissionCollection = new UserPermissionCollection();
        foreach ($permissions as $permission) {
            if (!$permission instanceof Permission) {
                continue;
            }

            if (Item::TYPE_PERMISSION === $permission->type) {
                $userPermissionCollection->add($this->createPermissionDto($permission));
            }
        }

        return $userPermissionCollection;
    }

    public function hasUserRole(UserRoleDto $userRoleDto, int $userId): bool
    {
        return array_key_exists($userRoleDto->getName(), $this->authManager->getRolesByUser($userId));
    }

    public function assignRole(UserRoleDto $userRoleDto, int $userId): UserAssignmentDto
    {
        $role = $this->authManager->getRole($userRoleDto->getName());
        if (!$role) {
            throw new UserRoleNotFoundException($userRoleDto->getName());
        }
        $assignment = $this->authManager->assign($role, $userId);

        return $this->createAssignmentDto($assignment);
    }

    public function revokeRole(UserRoleDto $userRoleDto, int $userId): bool
    {
        $role = $this->authManager->getRole($userRoleDto->getName());
        if (!$role) {
            throw new UserRoleNotFoundException($userRoleDto->getName());
        }

        return $this->authManager->revoke($role, $userId);
    }

    public function getPermissionsCollections(): UserPermissionCollection
    {
        $permissions = $this->getAllPermissions();
        $userPermissionCollection = new UserPermissionCollection();
        foreach ($permissions as $permission) {
            $userPermissionCollection->add($this->createPermissionDto($permission));
        }

        return $userPermissionCollection;
    }

    public function getPermission(string $permissionName): ?UserPermissionDto
    {
        $permission = $this->authManager->getPermission($permissionName);

        return $permission ? $this->createPermissionDto($permission) : null;
    }

    public function getPermissionsByRole(string $roleName): ?UserPermissionCollection
    {
        $permissions = $this->authManager->getPermissionsByRole($roleName);
        if (!$permissions) {
            return null;
        }
        $userPermissionCollection = new UserPermissionCollection();
        foreach ($permissions as $permission) {
            $userPermissionCollection->add($this->createPermissionDto($permission));
        }

        return $userPermissionCollection;
    }

    public function getPermissionsByUser(int $userId): ?UserPermissionCollection
    {
        $permissions = $this->authManager->getPermissionsByUser($userId);
        if (!$permissions) {
            return null;
        }
        $userPermissionCollection = new UserPermissionCollection();
        foreach ($permissions as $permission) {
            $dto = $this->createPermissionDto($permission);
            $dto->setStatus(true);
            $userPermissionCollection->add($dto);
        }

        return $userPermissionCollection;
    }

    public function hasUserAllPermissionOfRole(UserPermissionDto $userPermissionDto, int $userId): bool
    {
        $role = $this->getRoleByPermission($userPermissionDto);
        if (!$role) {
            return false;
        }
        $permissionsOfRole = array_map(
            fn (UserPermissionDto $userPermissionDto): string => $userPermissionDto->getName(),
            iterator_to_array($this->getPermissionsByRole($role->getName()) ?? new ArrayIterator())
        );
        $permissionsOfUser = array_map(
            fn (UserPermissionDto $userPermissionDto): string => $userPermissionDto->getName(),
            iterator_to_array($this->getPermissionsByUser($userId) ?? new ArrayIterator())
        );

        return empty(array_diff($permissionsOfRole, $permissionsOfUser));
    }

    public function revokeAllUserPermissionsByRole(UserRoleDto $userRoleDto, int $userId): void
    {
        $permissions = $this->getPermissionsByRole($userRoleDto->getName());
        foreach ($permissions ?? [] as $permission) {
            $this->revokePermission($permission, $userId);
        }
    }

    public function hasUserPermission(UserPermissionDto $userPermissionDto, int $userId): bool
    {
        return array_key_exists($userPermissionDto->getName(), $this->authManager->getPermissionsByUser($userId));
    }

    public function assignPermission(UserPermissionDto $userPermissionDto, int $userId): UserAssignmentDto
    {
        $permission = $this->authManager->getPermission($userPermissionDto->getName());
        if (!$permission) {
            throw new UserPermissionNotFoundException($userPermissionDto->getName());
        }
        $assignment = $this->authManager->assign($permission, $userId);

        return $this->createAssignmentDto($assignment);
    }

    public function revokePermission(UserPermissionDto $userPermissionDto, int $userId): bool
    {
        $permission = $this->authManager->getPermission($userPermissionDto->getName());
        if (!$permission) {
            throw new UserPermissionNotFoundException($userPermissionDto->getName());
        }

        return $this->authManager->revoke($permission, $userId);
    }

    public function can(string $accessName): bool
    {
        return Yii::$app->user->can($accessName);
    }

    /**
     * @return array<Role>
     */
    private function getAllRoles(): array
    {
        return $this->authManager->getRoles();
    }

    /**
     * @return array<Permission>
     */
    private function getAllPermissions(): array
    {
        return $this->authManager->getPermissions();
    }

    private function hasChild(Item $parent, Item $child): bool
    {
        return $this->authManager->hasChild($parent, $child);
    }

    private function createPermissionDto(Permission $permission): UserPermissionDto
    {
        return new UserPermissionDto(
            $permission->name,
            $permission->description,
            $permission->ruleName,
            $permission->data,
            $permission->createdAt,
            $permission->updatedAt
        );
    }

    private function createRoleDto(Role $role): UserRoleDto
    {
        return new UserRoleDto(
            $role->name,
            $role->description,
            $role->ruleName,
            $role->data,
            $role->createdAt,
            $role->updatedAt
        );
    }

    private function createAssignmentDto(Assignment $assignment): UserAssignmentDto
    {
        return new UserAssignmentDto(
            (int)$assignment->userId,
            $assignment->roleName,
            $assignment->createdAt
        );
    }
}
