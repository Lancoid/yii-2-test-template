<?php

/** @noinspection PhpGetterAndSetterCanBeReplacedWithPropertyHooksInspection */

declare(strict_types=1);

namespace app\modules\user\forms;

use app\modules\user\dictionaries\UserDictionary;
use app\modules\user\dictionaries\UserPermissionDictionary;
use app\modules\user\services\create\input\UserCreateInputInterface;
use app\modules\user\UserModule;
use yii\base\Model;
use yii\captcha\CaptchaValidator;

class UserRegistrationForm extends Model implements UserCreateInputInterface
{
    public ?string $username = null;

    public ?string $password = null;

    public ?string $email = null;

    public ?string $phone = null;

    public ?string $captcha = null;

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
                ['username', 'password', 'email', 'phone', 'captcha'],
                'required',
            ],

            [['username'], 'string', 'max' => 64],
            [['password'], 'string', 'max' => 64],
            [['email'], 'string', 'max' => 64],
            [['phone'], 'string'],
            [['phone'], 'match', 'pattern' => '/\d{11}/', 'message' => '«{attribute}» должен содержать 11 цифр.'],

            ['captcha', CaptchaValidator::class, 'captchaAction' => 'site/default/captcha'],

            [['email'], 'email', 'enableIDN' => true],
        ];
    }

    /**
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
        ];
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

    public function getRole(): ?string
    {
        return UserPermissionDictionary::ROLE_USER;
    }
}
