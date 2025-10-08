<?php

declare(strict_types=1);

namespace app\tests\unit\modules\core\services\authManager\dto;

use app\modules\core\services\authManager\dto\UserPermissionDto;
use Codeception\Test\Unit;

/**
 * @internal
 *
 * @covers \app\modules\core\services\authManager\dto\UserPermissionDto
 */
final class UserPermissionDtoTest extends Unit
{
    public function testPropertyName(): void
    {
        $userPermissionDto = new UserPermissionDto();

        $userPermissionDto->setName('perm');
        $this->assertSame('perm', $userPermissionDto->getName());
    }

    public function testPropertyDescription(): void
    {
        $userPermissionDto = new UserPermissionDto();

        $userPermissionDto->setDescription('desc');
        $this->assertSame('desc', $userPermissionDto->getDescription());
    }

    public function testPropertyRuleName(): void
    {
        $userPermissionDto = new UserPermissionDto();

        $userPermissionDto->setRuleName('rule');
        $this->assertSame('rule', $userPermissionDto->getRuleName());

        $userPermissionDto->setRuleName(null);
        $this->assertNull($userPermissionDto->getRuleName());
    }

    public function testPropertyData(): void
    {
        $userPermissionDto = new UserPermissionDto();

        $data = ['key' => 'value'];

        $userPermissionDto->setData($data);
        $this->assertSame($data, $userPermissionDto->getData());
    }

    public function testPropertyCreatedAt(): void
    {
        $userPermissionDto = new UserPermissionDto();

        $userPermissionDto->setCreatedAt(1234567890);
        $this->assertSame(1234567890, $userPermissionDto->getCreatedAt());
    }

    public function testPropertyUpdatedAt(): void
    {
        $userPermissionDto = new UserPermissionDto();

        $userPermissionDto->setUpdatedAt(987654321);
        $this->assertSame(987654321, $userPermissionDto->getUpdatedAt());
    }

    public function testPropertyStatus(): void
    {
        $userPermissionDto = new UserPermissionDto();

        $this->assertFalse($userPermissionDto->getStatus());

        $userPermissionDto->setStatus(true);
        $this->assertTrue($userPermissionDto->getStatus());

        $userPermissionDto->setStatus(false);
        $this->assertFalse($userPermissionDto->getStatus());
    }
}
