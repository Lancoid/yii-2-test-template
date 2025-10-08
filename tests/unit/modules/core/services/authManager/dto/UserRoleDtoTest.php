<?php

declare(strict_types=1);

namespace app\tests\unit\modules\core\services\authManager\dto;

use app\modules\core\services\authManager\dto\UserPermissionCollection;
use app\modules\core\services\authManager\dto\UserPermissionDto;
use app\modules\core\services\authManager\dto\UserRoleDto;
use Codeception\Test\Unit;

/**
 * @internal
 *
 * @covers \app\modules\core\services\authManager\dto\UserRoleDto
 */
final class UserRoleDtoTest extends Unit
{
    public function testPropertyName(): void
    {
        $userRoleDto = new UserRoleDto();
        $userRoleDto->setName('role');
        $this->assertSame('role', $userRoleDto->getName());
    }

    public function testPropertyDescription(): void
    {
        $userRoleDto = new UserRoleDto();
        $userRoleDto->setDescription('Role description');
        $this->assertSame('Role description', $userRoleDto->getDescription());
    }

    public function testPropertyRuleName(): void
    {
        $userRoleDto = new UserRoleDto();
        $userRoleDto->setRuleName('someRule');
        $this->assertSame('someRule', $userRoleDto->getRuleName());

        $userRoleDto->setRuleName(null);
        $this->assertNull($userRoleDto->getRuleName());
    }

    public function testPropertyData(): void
    {
        $userRoleDto = new UserRoleDto();
        $data = ['k' => 'v'];
        $userRoleDto->setData($data);
        $this->assertSame($data, $userRoleDto->getData());
    }

    public function testPropertyCreatedAt(): void
    {
        $userRoleDto = new UserRoleDto();
        $userRoleDto->setCreatedAt(1111111111);
        $this->assertSame(1111111111, $userRoleDto->getCreatedAt());
    }

    public function testPropertyUpdatedAt(): void
    {
        $userRoleDto = new UserRoleDto();
        $userRoleDto->setUpdatedAt(2222222222);
        $this->assertSame(2222222222, $userRoleDto->getUpdatedAt());
    }

    public function testPropertyStatus(): void
    {
        $userRoleDto = new UserRoleDto();

        // default should be false
        $this->assertFalse($userRoleDto->getStatus());

        $userRoleDto->setStatus(true);
        $this->assertTrue($userRoleDto->getStatus());

        $userRoleDto->setStatus(false);
        $this->assertFalse($userRoleDto->getStatus());
    }

    public function testHasAllPermissionEnabledFlag(): void
    {
        $userRoleDto = new UserRoleDto();

        // default should be true
        $this->assertTrue($userRoleDto->isHasAllPermissionEnabled());

        $userRoleDto->setHasAllPermissionEnabled(false);
        $this->assertFalse($userRoleDto->isHasAllPermissionEnabled());

        $userRoleDto->setHasAllPermissionEnabled(true);
        $this->assertTrue($userRoleDto->isHasAllPermissionEnabled());
    }

    public function testPermissionsCollection(): void
    {
        $userRoleDto = new UserRoleDto();

        // default should be null
        $this->assertNull($userRoleDto->getPermissions());

        $userPermissionCollection = new UserPermissionCollection();

        $perm1 = new UserPermissionDto();
        $perm1->setName('perm.1');
        $perm1->setDescription('desc1');
        $perm1->setRuleName(null);
        $perm1->setData(null);
        $perm1->setCreatedAt(1);
        $perm1->setUpdatedAt(2);
        $perm1->setStatus(true);

        $perm2 = new UserPermissionDto();
        $perm2->setName('perm.2');
        $perm2->setDescription('desc2');
        $perm2->setRuleName('rule2');
        $perm2->setData(['x' => 1]);
        $perm2->setCreatedAt(3);
        $perm2->setUpdatedAt(4);
        $perm2->setStatus(false);

        $userPermissionCollection->add($perm1);
        $userPermissionCollection->add($perm2);

        $userRoleDto->setPermission($userPermissionCollection);

        $this->assertSame($userPermissionCollection, $userRoleDto->getPermissions());
        $this->assertEquals(2, $userRoleDto->getPermissions()->count());
    }
}
