<?php

/** @noinspection PhpGetterAndSetterCanBeReplacedWithPropertyHooksInspection */

declare(strict_types=1);

namespace app\modules\user\forms;

use app\modules\user\services\login\input\UserLoginInputInterface;
use app\modules\user\UserModule;
use yii\base\Model;
use yii\captcha\CaptchaValidator;

class UserLoginForm extends Model implements UserLoginInputInterface
{
    public ?string $email = null;
    public ?string $password = null;
    public ?string $captcha = null;
    public bool $rememberMe = true;

    public function rules(): array
    {
        return [
            [['email', 'password'], 'trim'],

            [['email', 'password', 'captcha'], 'required'],

            [['email'], 'string', 'max' => 64],
            [['password'], 'string', 'max' => 64],
            [['rememberMe'], 'boolean'],

            ['captcha', CaptchaValidator::class, 'captchaAction' => 'core/default/captcha'],

            [['email'], 'email', 'enableIDN' => true],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributeLabels(): array
    {
        return [
            'email' => UserModule::t('login_form', 'email'),
            'password' => UserModule::t('login_form', 'password'),
            'captcha' => UserModule::t('login_form', 'captcha'),
            'rememberMe' => UserModule::t('login_form', 'rememberMe'),
        ];
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRememberMe(): bool
    {
        return $this->rememberMe;
    }
}
