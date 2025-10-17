<?php

declare(strict_types=1);

namespace app\modules\core\validators;

use app\modules\core\CoreModule;
use yii\base\Model;
use yii\validators\StringValidator;

/**
 * Validator for password strength and length.
 * Checks password for minimum length, maximum length, and complexity requirements.
 */
class PasswordValidator extends StringValidator
{
    /**
     * Regular expression for password complexity validation.
     * Requires at least one lowercase, one uppercase letter, and one digit.
     */
    public const string PASSWORD_REGEX = '/^(?=.*[a-zа-яё])(?=.*[A-ZА-ЯЁ])(?=.*\d)[A-ZА-ЯЁa-zа-яё\d~!?@#$%^&*_\-+()\[\]{}\/|"\'.,:;\\\]{3,}$/u';

    /**
     * Initializes error messages for password validation.
     */
    public function init(): void
    {
        $this->tooShort = CoreModule::t('validators', 'passwordTooShort');
        $this->tooLong = CoreModule::t('validators', 'passwordTooLong');
        $this->message = CoreModule::t('validators', 'passwordRegexError');

        parent::init();
    }

    /**
     * Validates the specified attribute of the model.
     *
     * @param Model $model The data model being validated
     * @param string $attribute The attribute being validated
     */
    public function validateAttribute($model, $attribute): void
    {
        /** @var string $attributeValue */
        $attributeValue = $model->{$attribute};

        if (!is_string($attributeValue) || !$this->isValidPassword($attributeValue)) {
            $this->addError($model, $attribute, $this->message);
        }

        parent::validateAttribute($model, $attribute);
    }

    /**
     * Checks if the password matches the complexity requirements.
     *
     * @param string $password The password to validate
     *
     * @return bool True if valid, false otherwise
     */
    private function isValidPassword(string $password): bool
    {
        return 1 === preg_match(self::PASSWORD_REGEX, $password);
    }
}
