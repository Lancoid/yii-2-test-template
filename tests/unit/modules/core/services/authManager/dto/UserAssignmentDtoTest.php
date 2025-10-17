<?php

declare(strict_types=1);

namespace app\tests\unit\modules\core\services\authManager\dto;

use app\modules\core\services\authManager\dto\UserAssignmentDto;
use Codeception\Test\Unit;

/**
 * Unit tests for UserAssignmentDto.
 *
 * Verifies constructor, property setters and getters.
 *
 * @internal
 *
 * @covers \app\modules\core\services\authManager\dto\UserAssignmentDto
 */
final class UserAssignmentDtoTest extends Unit
{
    /**
     * @dataProvider successProvider
     */
    public function testSuccess(int $userId, string $accessName, int $createdAt): void
    {
        $userAssignmentDto = new UserAssignmentDto($userId, $accessName, $createdAt);

        $this->assertEquals($userId, $userAssignmentDto->getUserId());
        $this->assertEquals($accessName, $userAssignmentDto->getAccessName());
        $this->assertEquals($createdAt, $userAssignmentDto->getCreatedAt());

        $userAssignmentDto->setUserId(1);
        $userAssignmentDto->setAccessName('test');
        $userAssignmentDto->setCreatedAt(1700000000);

        $this->assertEquals(1, $userAssignmentDto->getUserId());
        $this->assertEquals('test', $userAssignmentDto->getAccessName());
        $this->assertEquals(1700000000, $userAssignmentDto->getCreatedAt());
    }

    /**
     * Provides data for success test.
     *
     * @return array<int, array{
     *     userId: int,
     *     accessName: string,
     *     createdAt: int
     * }>
     */
    public static function successProvider(): array
    {
        return [
            ['userId' => 7, 'accessName' => 'admin', 'createdAt' => 1696780800],
            ['userId' => 42, 'accessName' => 'editor', 'createdAt' => 1600000000],
        ];
    }
}
