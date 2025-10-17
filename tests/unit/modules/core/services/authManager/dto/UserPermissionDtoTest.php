<?php

declare(strict_types=1);

namespace app\tests\unit\modules\core\services\authManager\dto;

use app\modules\core\services\authManager\dto\UserPermissionDto;
use Codeception\Test\Unit;

/**
 * Unit tests for UserPermissionDto.
 *
 * @internal
 *
 * @covers \app\modules\core\services\authManager\dto\UserPermissionDto
 */
final class UserPermissionDtoTest extends Unit
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
        bool $status
    ): void {
        $userPermissionDto = new UserPermissionDto(
            $name, $description, $ruleName, $data, $createdAt, $updatedAt, $status
        );

        $this->assertSame($name, $userPermissionDto->getName());
        $this->assertSame($description, $userPermissionDto->getDescription());
        $this->assertSame($ruleName, $userPermissionDto->getRuleName());
        $this->assertSame($data, $userPermissionDto->getData());
        $this->assertSame($createdAt, $userPermissionDto->getCreatedAt());
        $this->assertSame($updatedAt, $userPermissionDto->getUpdatedAt());
        $this->assertSame($status, $userPermissionDto->getStatus());

        $userPermissionDto->setName('name');
        $userPermissionDto->setDescription('description');
        $userPermissionDto->setRuleName('rule_name');
        $userPermissionDto->setData(['data' => 'data']);
        $userPermissionDto->setCreatedAt(1700000000);
        $userPermissionDto->setUpdatedAt(1700000001);
        $userPermissionDto->setStatus(true);

        $this->assertSame('name', $userPermissionDto->getName());
        $this->assertSame('description', $userPermissionDto->getDescription());
        $this->assertSame('rule_name', $userPermissionDto->getRuleName());
        $this->assertSame(['data' => 'data'], $userPermissionDto->getData());
        $this->assertSame(1700000000, $userPermissionDto->getCreatedAt());
        $this->assertSame(1700000001, $userPermissionDto->getUpdatedAt());
        $this->assertSame(true, $userPermissionDto->getStatus());
    }

    /**
     * Provides data for success test.
     *
     * @return array<int, array{
     *     name: string,
     *     description: string,
     *     ruleName: ?string,
     *     data: ?array<string, mixed>,
     *     createdAt:int,
     *     updatedAt: int,
     *     status: bool
     * }>
     */
    public static function successProvider(): array
    {
        return [
            [
                'name' => 'perm1',
                'description' => 'desc1',
                'ruleName' => 'rule1',
                'data' => ['foo' => 'bar'],
                'createdAt' => 123,
                'updatedAt' => 456,
                'status' => true,
            ],
            [
                'name' => 'perm2',
                'description' => 'desc2',
                'ruleName' => null,
                'data' => null,
                'createdAt' => 789,
                'updatedAt' => 1011,
                'status' => false,
            ],
        ];
    }
}
