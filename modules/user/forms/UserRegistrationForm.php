<?php

/** @noinspection PhpGetterAndSetterCanBeReplacedWithPropertyHooksInspection */

declare(strict_types=1);

namespace app\modules\user\forms;

use app\modules\core\validators\PasswordValidator;
use app\modules\core\validators\WordCountValidator;
use app\modules\user\dataProviders\UserDataProviderInterface;
use app\modules\user\dictionaries\UserDictionary;
use app\modules\user\dictionaries\UserPermissionDictionary;
use app\modules\user\services\create\input\UserCreateInputInterface;
use app\modules\user\UserModule;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\captcha\CaptchaValidator;
use yii\di\NotInstantiableException;
use yii\validators\InlineValidator;

/**
 * User registration form model.
 * Handles user registration input validation and attribute labels.
 */
class UserRegistrationForm extends Model implements UserCreateInputInterface
{
    /**
     * Username for registration.
     */
    public ?string $username = null;

    /**
     * Password for registration.
     */
    public ?string $password = null;

    /**
     * Email address for registration.
     */
    public ?string $email = null;

    /**
     * Phone number for registration.
     */
    public ?string $phone = null;

    /**
     * Captcha input.
     */
    public ?string $captcha = null;

    /**
     * Agreement to personal data processing.
     */
    public ?int $agreementPersonalData = null;

    /**
     * Returns validation rules for user registration form.
     */
    public function rules(): array
    {
        return [
            [
                ['username', 'password', 'email', 'phone'],
                'trim',
            ],
            [
                ['phone'],
                'filter',
                'filter' => function ($value) {
                    if (!is_string($value)) {
                        return $value;
                    }

                    return substr(preg_replace('/\D/', '', trim($value)) ?? '', -11);
                },
            ],
            [
                [
                    'username',
                    'password',
                    'email',
                    'phone',
                    'captcha',
                    'agreementPersonalData',
                ],
                'required',
            ],
            [['username', 'password', 'email'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 11],
            [['agreementPersonalData'], 'integer'],
            [['phone'], 'match', 'pattern' => '/\d{11}/', 'message' => UserModule::t('validators', 'phoneFormatError')],
            [['email'], 'email', 'enableIDN' => true],
            [['email'], 'validateEmail'],
            [['username'], WordCountValidator::class, 'max' => 4],
            [['password'], PasswordValidator::class, 'min' => 10, 'max' => 60],
            [['captcha'], CaptchaValidator::class, 'captchaAction' => 'core/default/captcha'],
            [
                ['agreementPersonalData'],
                'compare',
                'compareValue' => UserDictionary::AGREEMENT_TO_PROCESSING_PERSONAL_DATA,
                'operator' => '===',
                'type' => 'number',
                'message' => UserModule::t('validators', 'agreementPersonalDataMustSelected'),
            ],
        ];
    }

    /**
     * Returns attribute labels for user registration form.
     *
     * @return array<string, string>
     */
    public function attributeLabels(): array
    {
        return [
            'username' => UserModule::t('registration_form', 'username'),
            'password' => UserModule::t('registration_form', 'password'),
            'email' => UserModule::t('registration_form', 'email'),
            'phone' => UserModule::t('registration_form', 'phone'),
            'captcha' => UserModule::t('registration_form', 'captcha'),
            'agreementPersonalData' => UserModule::t('registration_form', 'agreementPersonalData'),
        ];
    }

    /**
     * Validates that the email is not already used by another user.
     *
     * @param string $attribute attribute name
     * @param array $params additional parameters
     * @param InlineValidator $inlineValidator inline validator instance
     *
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function validateEmail(string $attribute, array $params, InlineValidator $inlineValidator): void
    {
        /** @var UserDataProviderInterface $userDataProvider */
        $userDataProvider = Yii::$container->get(UserDataProviderInterface::class);

        /** @var string $attributeValue */
        $attributeValue = $this->{$attribute};

        $isUserExist = $userDataProvider->existByEmail($attributeValue);

        if ($isUserExist) {
            $inlineValidator->addError($this, $attribute, UserModule::t('validators', 'existUserEmail'), $params);
        }
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getStatus(): ?int
    {
        return UserDictionary::STATUS_ACTIVE;
    }

    public function getAgreementPersonalData(): ?int
    {
        return $this->agreementPersonalData;
    }

    public function getRole(): ?string
    {
        return UserPermissionDictionary::ROLE_USER;
    }
}
