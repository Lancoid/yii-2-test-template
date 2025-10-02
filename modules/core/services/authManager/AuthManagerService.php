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
            $dto = $this->fillRoleDto($role);

            $userRoleCollection->add($dto);
        }

        return $userRoleCollection;
    }

    public function getRole(string $roleName): ?UserRoleDto
    {
        $role = $this->authManager->getRole($roleName);

        if (!$role) {
            return null;
        }

        return $this->fillRoleDto($role);
    }

    public function getRoleByPermission(UserPermissionDto $userPermissionDto): ?UserRoleDto
    {
        $userPermissionDto = $this->authManager->getPermission($userPermissionDto->getName());

        if (!$userPermissionDto) {
            return null;
        }

        $roles = $this->getAllRoles();

        foreach ($roles as $role) {
            if ($this->hasChild($role, $userPermissionDto)) {
                return $this->fillRoleDto($role);
            }
        }

        return null;
    }

    public function getRolesByUser(int $userId): ?UserRoleCollection
    {
        $data = $this->authManager->getRolesByUser($userId);

        if (!$data) {
            return null;
        }

        $userRoleCollection = new UserRoleCollection();

        foreach ($data as $role) {
            $dto = $this->fillRoleDto($role);
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
            if (Item::TYPE_PERMISSION !== $permission->type) {
                continue;
            }

            $dto = new UserPermissionDto();

            $dto->setName($permission->name);
            $dto->setDescription($permission->description);
            $dto->setRuleName($permission->ruleName);
            $dto->setData($permission->data);
            $dto->setCreatedAt($permission->createdAt);
            $dto->setUpdatedAt($permission->updatedAt);

            $userPermissionCollection->add($dto);
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

        return $this->fillAssignmentDto($assignment);
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
            $dto = $this->fillPermissionDto($permission);

            $userPermissionCollection->add($dto);
        }

        return $userPermissionCollection;
    }

    public function getPermission(string $permissionName): ?UserPermissionDto
    {
        $permission = $this->authManager->getPermission($permissionName);

        if (!$permission) {
            return null;
        }

        return $this->fillPermissionDto($permission);
    }

    public function getPermissionsByRole(string $roleName): ?UserPermissionCollection
    {
        $permissions = $this->authManager->getPermissionsByRole($roleName);

        if (!$permissions) {
            return null;
        }

        $userPermissionCollection = new UserPermissionCollection();

        foreach ($permissions as $key => $permission) {
            $userPermissionCollection->add($this->fillPermissionDto($permission));

            unset($permissions[$key]);
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

        foreach ($permissions as $key => $permission) {
            $dto = $this->fillPermissionDto($permission);
            $dto->setStatus(true);

            $userPermissionCollection->add($dto);

            unset($permissions[$key]);
        }

        return $userPermissionCollection;
    }

    public function hasUserAllPermissionOfRole(UserPermissionDto $userPermissionDto, int $userId): bool
    {
        $role = $this->getRoleByPermission($userPermissionDto);

        if (!$role instanceof UserRoleDto) {
            return false;
        }

        $this->getRolesByUser($userId);

        $permissionsOfRole = [];
        foreach ($this->getPermissionsByRole($role->getName()) ?? [] as $permission) {
            $permissionsOfRole[] = $permission->getName();
        }

        $permissionsOfUser = [];
        foreach ($this->getPermissionsByUser($userId) ?? [] as $permission) {
            $permissionsOfUser[] = $permission->getName();
        }

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

        return $this->fillAssignmentDto($assignment);
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

    private function fillPermissionDto(Permission $permission): UserPermissionDto
    {
        $userPermissionDto = new UserPermissionDto();

        $userPermissionDto->setName($permission->name);
        $userPermissionDto->setDescription($permission->description);
        $userPermissionDto->setRuleName($permission->ruleName);
        $userPermissionDto->setData($permission->data);
        $userPermissionDto->setCreatedAt($permission->createdAt);
        $userPermissionDto->setUpdatedAt($permission->updatedAt);

        return $userPermissionDto;
    }

    private function fillRoleDto(Role $role): UserRoleDto
    {
        $userRoleDto = new UserRoleDto();

        $userRoleDto->setName($role->name);
        $userRoleDto->setDescription($role->description);
        $userRoleDto->setRuleName($role->ruleName);
        $userRoleDto->setData($role->data);
        $userRoleDto->setCreatedAt($role->createdAt);
        $userRoleDto->setUpdatedAt($role->updatedAt);

        return $userRoleDto;
    }

    private function hasChild(Item $parent, Item $child): bool
    {
        return $this->authManager->hasChild($parent, $child);
    }

    private function fillAssignmentDto(Assignment $assignment): UserAssignmentDto
    {
        $userAssignmentDto = new UserAssignmentDto();
        $userAssignmentDto->setUserId((int)$assignment->userId);
        $userAssignmentDto->setAccessName($assignment->roleName);
        $userAssignmentDto->setCreatedAt($assignment->createdAt);

        return $userAssignmentDto;
    }
}
