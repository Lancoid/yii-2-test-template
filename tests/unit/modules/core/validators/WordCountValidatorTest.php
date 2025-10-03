<?php

declare(strict_types=1);

namespace app\tests\unit\modules\core\validators;

use app\modules\core\validators\WordCountValidator;
use Codeception\Test\Unit;
use yii\base\DynamicModel;

/**
 * @internal
 *
 * @coversNothing
 */
final class WordCountValidatorTest extends Unit
{
    public function testValidationCorrectWithinRange(): void
    {
        $dynamicModel = $this->makeModel('one two three', 2, 4);

        $this->assertTrue($dynamicModel->validate());
        $this->assertFalse($dynamicModel->hasErrors('text'));
    }

    public function testTooShortWhenBelowMin(): void
    {
        $dynamicModel = $this->makeModel('one', 2);

        $this->assertFalse($dynamicModel->validate());
        $this->assertTrue($dynamicModel->hasErrors('text'));
        $this->assertCount(1, $dynamicModel->getErrors('text'));
    }

    public function testTooLongWhenAboveMax(): void
    {
        $dynamicModel = $this->makeModel('one two three four five six', null, 5);

        $this->assertFalse($dynamicModel->validate());
        $this->assertTrue($dynamicModel->hasErrors('text'));
        $this->assertCount(1, $dynamicModel->getErrors('text'));
    }

    public function testMultipleSpacesAreHandled(): void
    {
        $dynamicModel = $this->makeModel('one   two    three', 3, 3);

        $this->assertTrue($dynamicModel->validate());
        $this->assertFalse($dynamicModel->hasErrors('text'));
    }

    private function makeModel(string $text, ?int $min = null, ?int $max = null): DynamicModel
    {
        $dynamicModel = new DynamicModel(['text' => $text]);

        $options = [];
        if (null !== $min) {
            $options['min'] = $min;
        }
        if (null !== $max) {
            $options['max'] = $max;
        }

        $dynamicModel->addRule('text', WordCountValidator::class, $options);

        return $dynamicModel;
    }
}
