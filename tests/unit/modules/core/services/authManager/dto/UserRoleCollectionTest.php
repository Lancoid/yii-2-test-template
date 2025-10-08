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
        $userRoleDto = new UserRoleDto();
        $userRoleDto->setName($name);
        $userRoleDto->setDescription('desc-' . $name);
        $userRoleDto->setRuleName(null);
        $userRoleDto->setData(null);
        $userRoleDto->setCreatedAt(1);
        $userRoleDto->setUpdatedAt(1);
        $userRoleDto->setStatus(false);

        return $userRoleDto;
    }

    public function testAddAndCountAndIteration(): void
    {
        $userRoleCollection = new UserRoleCollection();

        // initially empty
        $this->assertSame(0, $userRoleCollection->count());

        $userRoleDto = $this->makeRole('admin');
        $user = $this->makeRole('user');

        $userRoleCollection->add($userRoleDto);
        $userRoleCollection->add($user);

        $this->assertSame(2, $userRoleCollection->count());

        // iterate and collect names to ensure iterator methods work
        $names = [];
        foreach ($userRoleCollection as $role) {
            $this->assertInstanceOf(UserRoleDto::class, $role);
            $names[] = $role->getName();
        }

        $this->assertSame(['admin', 'user'], $names);
    }

    public function testExist(): void
    {
        $userRoleCollection = new UserRoleCollection();
        $userRoleCollection->add($this->makeRole('manager'));

        $this->assertTrue($userRoleCollection->exist('manager'));
        $this->assertFalse($userRoleCollection->exist('unknown'));
        $this->assertFalse((new UserRoleCollection())->exist('any'));
    }

    public function testRemove(): void
    {
        $userRoleCollection = new UserRoleCollection();
        $userRoleCollection->add($this->makeRole('r1')); // index 0
        $userRoleCollection->add($this->makeRole('r2')); // index 1
        $userRoleCollection->add($this->makeRole('r3')); // index 2

        $this->assertSame(3, $userRoleCollection->count());

        // remove existing key
        $userRoleCollection->remove(1);
        $this->assertSame(2, $userRoleCollection->count());

        // removing non-existent key should not change count
        $userRoleCollection->remove(10);
        $this->assertSame(2, $userRoleCollection->count());
    }

    public function testIntersect(): void
    {
        $userRoleCollection = new UserRoleCollection();
        $userRoleDto = $this->makeRole('alpha');
        $r2 = $this->makeRole('beta');
        $r3 = $this->makeRole('gamma');
        $userRoleCollection->add($userRoleDto);
        $userRoleCollection->add($r2);
        $userRoleCollection->add($r3);

        $result = $userRoleCollection->intersect(['beta', 'unknown', 'alpha']);

        // Should return only matching roles, preserving collection order (beta comes after alpha in filter list but keeps collection order)
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertSame('alpha', $result[0]->getName());
        $this->assertSame('beta', $result[1]->getName());
    }
}
