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
        $dto = new UserAssignmentDto();
        $dto->setUserId(42);
        $this->assertEquals(42, $dto->getUserId());
    }

    public function testPropertyAccessName(): void
    {
        $dto = new UserAssignmentDto();
        $dto->setAccessName('admin');
        $this->assertEquals('admin', $dto->getAccessName());
    }

    public function testPropertyCreatedAt(): void
    {
        $dto = new UserAssignmentDto();
        $dto->setCreatedAt(1696780800);
        $this->assertEquals(1696780800, $dto->getCreatedAt());
    }
}
