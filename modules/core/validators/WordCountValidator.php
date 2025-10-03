<?php

declare(strict_types=1);

namespace app\modules\core\validators;

use app\modules\core\CoreModule;
use yii\base\Model;
use yii\validators\StringValidator;

class WordCountValidator extends StringValidator
{
    public function init(): void
    {
        $this->tooShort = CoreModule::t('validators', 'passwordTooShort');
        $this->tooLong = CoreModule::t('validators', 'passwordTooLong');

        parent::init();
    }

    /**
     * @param Model $model the data model being validated
     * @param string $attribute the attribute being validated
     */
    public function validateAttribute($model, $attribute): void
    {
        /** @var string $attributeValue */
        $attributeValue = $model->{$attribute};

        $wordCount = count(preg_split('/\s+/', $attributeValue) ?: []);

        if (!empty($this->min) && $wordCount < $this->min) {
            $this->addError($model, $attribute, $this->tooShort);
        }

        if (!empty($this->max) && $wordCount > $this->max) {
            $this->addError($model, $attribute, $this->tooLong);
        }
    }
}
