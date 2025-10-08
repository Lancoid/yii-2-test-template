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
        $dto = new UserRoleDto();
        $dto->setName('role');
        $this->assertSame('role', $dto->getName());
    }

    public function testPropertyDescription(): void
    {
        $dto = new UserRoleDto();
        $dto->setDescription('Role description');
        $this->assertSame('Role description', $dto->getDescription());
    }

    public function testPropertyRuleName(): void
    {
        $dto = new UserRoleDto();
        $dto->setRuleName('someRule');
        $this->assertSame('someRule', $dto->getRuleName());

        $dto->setRuleName(null);
        $this->assertNull($dto->getRuleName());
    }

    public function testPropertyData(): void
    {
        $dto = new UserRoleDto();
        $data = ['k' => 'v'];
        $dto->setData($data);
        $this->assertSame($data, $dto->getData());
    }

    public function testPropertyCreatedAt(): void
    {
        $dto = new UserRoleDto();
        $dto->setCreatedAt(1111111111);
        $this->assertSame(1111111111, $dto->getCreatedAt());
    }

    public function testPropertyUpdatedAt(): void
    {
        $dto = new UserRoleDto();
        $dto->setUpdatedAt(2222222222);
        $this->assertSame(2222222222, $dto->getUpdatedAt());
    }

    public function testPropertyStatus(): void
    {
        $dto = new UserRoleDto();

        // default should be false
        $this->assertFalse($dto->getStatus());

        $dto->setStatus(true);
        $this->assertTrue($dto->getStatus());

        $dto->setStatus(false);
        $this->assertFalse($dto->getStatus());
    }

    public function testHasAllPermissionEnabledFlag(): void
    {
        $dto = new UserRoleDto();

        // default should be true
        $this->assertTrue($dto->isHasAllPermissionEnabled());

        $dto->setHasAllPermissionEnabled(false);
        $this->assertFalse($dto->isHasAllPermissionEnabled());

        $dto->setHasAllPermissionEnabled(true);
        $this->assertTrue($dto->isHasAllPermissionEnabled());
    }

    public function testPermissionsCollection(): void
    {
        $dto = new UserRoleDto();

        // default should be null
        $this->assertNull($dto->getPermissions());

        $collection = new UserPermissionCollection();

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

        $collection->add($perm1);
        $collection->add($perm2);

        $dto->setPermission($collection);

        $this->assertSame($collection, $dto->getPermissions());
        $this->assertEquals(2, $dto->getPermissions()->count());
    }
}
