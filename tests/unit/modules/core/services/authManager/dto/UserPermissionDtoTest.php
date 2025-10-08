<?php

declare(strict_types=1);

namespace app\tests\unit\modules\core\services\authManager\dto;

use Codeception\Test\Unit;
use app\modules\core\services\authManager\dto\UserPermissionDto;

/**
 * @internal
 *
 * @covers \app\modules\core\services\authManager\dto\UserPermissionDto
 */
class UserPermissionDtoTest extends Unit
{
    public function testPropertyName(): void
    {
        $dto = new UserPermissionDto();

        $dto->setName('perm');
        $this->assertSame('perm', $dto->getName());
    }

    public function testPropertyDescription(): void
    {
        $dto = new UserPermissionDto();

        $dto->setDescription('desc');
        $this->assertSame('desc', $dto->getDescription());
    }

    public function testPropertyRuleName(): void
    {
        $dto = new UserPermissionDto();

        $dto->setRuleName('rule');
        $this->assertSame('rule', $dto->getRuleName());

        $dto->setRuleName(null);
        $this->assertNull($dto->getRuleName());
    }

    public function testPropertyData(): void
    {
        $dto = new UserPermissionDto();

        $data = ['key' => 'value'];

        $dto->setData($data);
        $this->assertSame($data, $dto->getData());
    }

    public function testPropertyCreatedAt(): void
    {
        $dto = new UserPermissionDto();

        $dto->setCreatedAt(1234567890);
        $this->assertSame(1234567890, $dto->getCreatedAt());
    }

    public function testPropertyUpdatedAt(): void
    {
        $dto = new UserPermissionDto();

        $dto->setUpdatedAt(987654321);
        $this->assertSame(987654321, $dto->getUpdatedAt());
    }

    public function testPropertyStatus(): void
    {
        $dto = new UserPermissionDto();

        $this->assertFalse($dto->getStatus());

        $dto->setStatus(true);
        $this->assertTrue($dto->getStatus());

        $dto->setStatus(false);
        $this->assertFalse($dto->getStatus());
    }
}

