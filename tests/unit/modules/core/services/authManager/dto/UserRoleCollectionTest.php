<?php

declare(strict_types=1);

namespace app\tests\unit\modules\core\services\authManager\dto;

use app\modules\core\services\authManager\dto\UserRoleCollection;
use app\modules\core\services\authManager\dto\UserRoleDto;
use Codeception\Test\Unit;

/**
 * Unit test for UserRoleCollection using dataProvider.
 *
 * @internal
 *
 * @covers \app\modules\core\services\authManager\dto\UserRoleCollection
 */
final class UserRoleCollectionTest extends Unit
{
    /**
     * @dataProvider successProvider
     *
     * @param UserRoleDto[] $initialRoles
     * @param UserRoleDto[] $newRoles
     */
    public function testSuccess(array $initialRoles, array $newRoles): void
    {
        // Create collection and add initial roles
        $collection = new UserRoleCollection();
        foreach ($initialRoles as $role) {
            $collection->add($role);
        }

        // Check getters after constructor
        $this->assertSame(count($initialRoles), $collection->count());
        $names = [];
        foreach ($collection as $role) {
            $this->assertInstanceOf(UserRoleDto::class, $role);
            $names[] = $role->getName();
        }
        $this->assertSame(['admin', 'user'], $names);

        // Set new roles using add (simulate setter)
        $collection = new UserRoleCollection();
        foreach ($newRoles as $role) {
            $collection->add($role);
        }

        // Check getters after setters
        $this->assertSame(count($newRoles), $collection->count());
        $newNames = [];
        foreach ($collection as $role) {
            $newNames[] = $role->getName();
        }
        $this->assertSame(['manager', 'guest'], $newNames);
    }

    /**
     * Data provider for testSuccess.
     *
     * @return array<array<UserRoleDto[]>>
     */
    public static function successProvider(): array
    {
        return [
            [
                [
                    new UserRoleDto('admin', 'desc-admin', null, ['x' => 1], 10, 20, true),
                    new UserRoleDto('user', 'desc-user', 'rule', null, 30, 40, false),
                ],
                [
                    new UserRoleDto('manager', 'desc-manager', 'newRule', ['y' => 2], 50, 60, true),
                    new UserRoleDto('guest', 'desc-guest', null, null, 70, 80, false),
                ],
            ],
        ];
    }
}
