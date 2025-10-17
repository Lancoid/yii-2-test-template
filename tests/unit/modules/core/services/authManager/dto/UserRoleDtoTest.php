<?php

declare(strict_types=1);

namespace app\tests\unit\modules\core\services\authManager\dto;

use app\modules\core\services\authManager\dto\UserPermissionCollection;
use app\modules\core\services\authManager\dto\UserRoleDto;
use Codeception\Test\Unit;

/**
 * Unit test for UserRoleDto.
 *
 * @internal
 *
 * @covers \app\modules\core\services\authManager\dto\UserRoleDto
 */
final class UserRoleDtoTest extends Unit
{
    /**
     * @dataProvider successProvider
     */
    public function testSuccess(
        string $name,
        string $description,
        ?string $ruleName,
        ?array $data,
        int $createdAt,
        int $updatedAt,
        bool $status,
        bool $hasAllPermissionEnabled,
        ?UserPermissionCollection $userPermissionCollection
    ): void {
        $userRoleDto = new UserRoleDto(
            $name,
            $description,
            $ruleName,
            $data,
            $createdAt,
            $updatedAt,
            $status,
            $hasAllPermissionEnabled,
            $userPermissionCollection
        );

        $this->assertSame($name, $userRoleDto->getName());
        $this->assertSame($description, $userRoleDto->getDescription());
        $this->assertSame($ruleName, $userRoleDto->getRuleName());
        $this->assertSame($data, $userRoleDto->getData());
        $this->assertSame($createdAt, $userRoleDto->getCreatedAt());
        $this->assertSame($updatedAt, $userRoleDto->getUpdatedAt());
        $this->assertSame($status, $userRoleDto->getStatus());
        $this->assertSame($hasAllPermissionEnabled, $userRoleDto->hasAllPermissionsEnabled());
        $this->assertSame($userPermissionCollection, $userRoleDto->getPermissions());

        $userRoleDto->setName('newName');
        $userRoleDto->setDescription('newDescription');
        $userRoleDto->setRuleName('newRule');
        $userRoleDto->setData(['y' => 2]);
        $userRoleDto->setCreatedAt(555);
        $userRoleDto->setUpdatedAt(666);
        $userRoleDto->setStatus(!$status);
        $userRoleDto->setHasAllPermissionsEnabled(!$hasAllPermissionEnabled);

        $newPermissions = new UserPermissionCollection();
        $userRoleDto->setPermissions($newPermissions);

        $this->assertSame('newName', $userRoleDto->getName());
        $this->assertSame('newDescription', $userRoleDto->getDescription());
        $this->assertSame('newRule', $userRoleDto->getRuleName());
        $this->assertSame(['y' => 2], $userRoleDto->getData());
        $this->assertSame(555, $userRoleDto->getCreatedAt());
        $this->assertSame(666, $userRoleDto->getUpdatedAt());
        $this->assertSame(!$status, $userRoleDto->getStatus());
        $this->assertSame(!$hasAllPermissionEnabled, $userRoleDto->hasAllPermissionsEnabled());
        $this->assertSame($newPermissions, $userRoleDto->getPermissions());
    }

    /**
     * Data provider for testRoleDto.
     *
     * @return array<int, array{
     *     name: string,
     *     description: string,
     *     ruleName: null|string,
     *     data: null|array<string,mixed>,
     *     createdAt:int,
     *     updatedAt: int,
     *     status: bool,
     *     hasAllPermissionEnabled: bool,
     *     userPermissionCollection: null|UserPermissionCollection
     * }>
     */
    public static function successProvider(): array
    {
        return [
            [
                'name' => 'admin',
                'description' => 'Administrator',
                'ruleName' => 'rule1',
                'data' => ['x' => 1],
                'createdAt' => 100,
                'updatedAt' => 200,
                'status' => true,
                'hasAllPermissionEnabled' => false,
                'userPermissionCollection' => new UserPermissionCollection(),
            ],
            [
                'name' => 'user',
                'description' => 'User',
                'ruleName' => null,
                'data' => null,
                'createdAt' => 300,
                'updatedAt' => 400,
                'status' => false,
                'hasAllPermissionEnabled' => true,
                'userPermissionCollection' => null,
            ],
        ];
    }
}
