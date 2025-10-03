<?php

declare(strict_types=1);

namespace app\modules\core\validators;

use app\modules\core\CoreModule;
use yii\base\Model;
use yii\validators\StringValidator;

class PasswordValidator extends StringValidator
{
    public const string PASSWORD_REGEX = '/^(?=.*[a-zа-яё])(?=.*[A-ZА-ЯЁ])(?=.*\d)[A-ZА-ЯЁa-zа-яё\d~!?@#$%^&*_\-+()\[\]{}\/|"\'.,:;\\\]{3,}$/u';

    public function init(): void
    {
        $this->tooShort = CoreModule::t('validators', 'passwordTooShort');
        $this->tooLong = CoreModule::t('validators', 'passwordTooLong');
        $this->message = CoreModule::t('validators', 'passwordRegexError');

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

        if (!preg_match(self::PASSWORD_REGEX, $attributeValue)) {
            $this->addError($model, $attribute, $this->message);
        }

        parent::validateAttribute($model, $attribute);
    }
}
