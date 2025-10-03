<?php

declare(strict_types=1);

namespace app\tests\unit\modules\core\validators;

use app\modules\core\validators\PasswordValidator;
use Codeception\Test\Unit;
use yii\base\DynamicModel;

/**
 * @internal
 *
 * @coversNothing
 */
final class PasswordValidatorTest extends Unit
{
    public function testValidationCorrect(): void
    {
        $dynamicModel = $this->makeModel('Aa1aaaaaaa', 10, 60);

        $this->assertTrue($dynamicModel->validate());
        $this->assertFalse($dynamicModel->hasErrors('password'));
    }

    public function testPasswordMissingUppercase(): void
    {
        $dynamicModel = $this->makeModel('aa1aaaaaaa');

        $this->assertFalse($dynamicModel->validate());
        $this->assertTrue($dynamicModel->hasErrors('password'));
    }

    public function testPasswordMissingLowercase(): void
    {
        $dynamicModel = $this->makeModel('AA1AAAAAAA');

        $this->assertFalse($dynamicModel->validate());
        $this->assertTrue($dynamicModel->hasErrors('password'));
    }

    public function testPasswordMissingDigit(): void
    {
        $dynamicModel = $this->makeModel('AaAAAAAAAA');

        $this->assertFalse($dynamicModel->validate());
        $this->assertTrue($dynamicModel->hasErrors('password'));
    }

    public function testPasswordTooShort(): void
    {
        $dynamicModel = $this->makeModel('Aa1aaaaaaa', 12);

        $this->assertFalse($dynamicModel->validate());
        $this->assertTrue($dynamicModel->hasErrors('password'));
        $this->assertCount(1, $dynamicModel->getErrors('password'));
    }

    public function testPasswordTooLong(): void
    {
        $dynamicModel = $this->makeModel('Aa1aaaa', null, 5);

        $this->assertFalse($dynamicModel->validate());
        $this->assertTrue($dynamicModel->hasErrors('password'));
        $this->assertCount(1, $dynamicModel->getErrors('password'));
    }

    public function testAllowedSpecialCharactersAccepted(): void
    {
        $password = 'Aa1~!?@#$%^&*_-+()[]{}\/|"\'.,:;';
        $dynamicModel = $this->makeModel($password);

        $this->assertTrue($dynamicModel->validate());
        $this->assertFalse($dynamicModel->hasErrors('password'));
    }

    private function makeModel(string $password, ?int $min = null, ?int $max = null): DynamicModel
    {
        $dynamicModel = new DynamicModel(['password' => $password]);

        $options = [];
        if (null !== $min) {
            $options['min'] = $min;
        }
        if (null !== $max) {
            $options['max'] = $max;
        }

        $dynamicModel->addRule('password', PasswordValidator::class, $options);

        return $dynamicModel;
    }
}
