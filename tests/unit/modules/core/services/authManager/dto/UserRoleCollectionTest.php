<?php

declare(strict_types=1);

namespace app\tests\unit\modules\core\services\authManager\dto;

use app\modules\core\services\authManager\dto\UserRoleCollection;
use app\modules\core\services\authManager\dto\UserRoleDto;
use Codeception\Test\Unit;

/**
 * @internal
 *
 * @covers \app\modules\core\services\authManager\dto\UserRoleCollection
 */
final class UserRoleCollectionTest extends Unit
{
    private function makeRole(string $name): UserRoleDto
    {
        $dto = new UserRoleDto();
        $dto->setName($name);
        $dto->setDescription('desc-' . $name);
        $dto->setRuleName(null);
        $dto->setData(null);
        $dto->setCreatedAt(1);
        $dto->setUpdatedAt(1);
        $dto->setStatus(false);

        return $dto;
    }

    public function testAddAndCountAndIteration(): void
    {
        $collection = new UserRoleCollection();

        // initially empty
        $this->assertSame(0, $collection->count());

        $admin = $this->makeRole('admin');
        $user = $this->makeRole('user');

        $collection->add($admin);
        $collection->add($user);

        $this->assertSame(2, $collection->count());

        // iterate and collect names to ensure iterator methods work
        $names = [];
        foreach ($collection as $role) {
            $this->assertInstanceOf(UserRoleDto::class, $role);
            $names[] = $role->getName();
        }

        $this->assertSame(['admin', 'user'], $names);
    }

    public function testExist(): void
    {
        $collection = new UserRoleCollection();
        $collection->add($this->makeRole('manager'));

        $this->assertTrue($collection->exist('manager'));
        $this->assertFalse($collection->exist('unknown'));
        $this->assertFalse((new UserRoleCollection())->exist('any'));
    }

    public function testRemove(): void
    {
        $collection = new UserRoleCollection();
        $collection->add($this->makeRole('r1')); // index 0
        $collection->add($this->makeRole('r2')); // index 1
        $collection->add($this->makeRole('r3')); // index 2

        $this->assertSame(3, $collection->count());

        // remove existing key
        $collection->remove(1);
        $this->assertSame(2, $collection->count());

        // removing non-existent key should not change count
        $collection->remove(10);
        $this->assertSame(2, $collection->count());
    }

    public function testIntersect(): void
    {
        $collection = new UserRoleCollection();
        $r1 = $this->makeRole('alpha');
        $r2 = $this->makeRole('beta');
        $r3 = $this->makeRole('gamma');
        $collection->add($r1);
        $collection->add($r2);
        $collection->add($r3);

        $result = $collection->intersect(['beta', 'unknown', 'alpha']);

        // Should return only matching roles, preserving collection order (beta comes after alpha in filter list but keeps collection order)
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertSame('alpha', $result[0]->getName());
        $this->assertSame('beta', $result[1]->getName());
    }
}
