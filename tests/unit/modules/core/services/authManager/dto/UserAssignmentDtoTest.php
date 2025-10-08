<?php

declare(strict_types=1);

namespace app\tests\unit\modules\core\services\authManager\dto;

use app\modules\core\services\authManager\dto\UserAssignmentDto;
use Codeception\Test\Unit;

/**
 * @internal
 *
 * @covers \app\modules\core\services\authManager\dto\UserAssignmentDto
 */
final class UserAssignmentDtoTest extends Unit
{
    public function testPropertyUserId(): void
    {
        $userAssignmentDto = new UserAssignmentDto();
        $userAssignmentDto->setUserId(42);
        $this->assertEquals(42, $userAssignmentDto->getUserId());
    }

    public function testPropertyAccessName(): void
    {
        $userAssignmentDto = new UserAssignmentDto();
        $userAssignmentDto->setAccessName('admin');
        $this->assertEquals('admin', $userAssignmentDto->getAccessName());
    }

    public function testPropertyCreatedAt(): void
    {
        $userAssignmentDto = new UserAssignmentDto();
        $userAssignmentDto->setCreatedAt(1696780800);
        $this->assertEquals(1696780800, $userAssignmentDto->getCreatedAt());
    }
}
