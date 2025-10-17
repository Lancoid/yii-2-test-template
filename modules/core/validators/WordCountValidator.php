<?php

declare(strict_types=1);

namespace app\modules\core\validators;

use app\modules\core\CoreModule;
use yii\base\Model;
use yii\validators\StringValidator;

/**
 * Validator for word count in a string attribute.
 * Checks if the number of words is within the specified min and max limits.
 */
class WordCountValidator extends StringValidator
{
    /**
     * Initializes error messages for word count validation.
     */
    public function init(): void
    {
        $this->tooShort = CoreModule::t('validators', 'passwordTooShort');
        $this->tooLong = CoreModule::t('validators', 'passwordTooLong');

        parent::init();
    }

    /**
     * Validates the specified attribute of the model for word count.
     *
     * @param Model $model The data model being validated
     * @param string $attribute The attribute being validated
     */
    public function validateAttribute($model, $attribute): void
    {
        $attributeValue = $model->{$attribute};

        if (!is_string($attributeValue)) {
            $this->addError($model, $attribute, $this->tooShort);

            return;
        }

        $wordCount = count(preg_split('/\s+/', $attributeValue) ?: []);

        if (!empty($this->min) && $wordCount < $this->min) {
            $this->addError($model, $attribute, $this->tooShort);
        }

        if (!empty($this->max) && $wordCount > $this->max) {
            $this->addError($model, $attribute, $this->tooLong);
        }
    }
}
