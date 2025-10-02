<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use app\modules\user\forms\UserLoginForm;
use app\modules\user\UserModule;
use juliardi\captcha\Captcha;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveForm $form
 * @var UserLoginForm $loginForm
 */
$this->title = UserModule::t('login_form', 'loginTitle');

$emailFieldOptions = [
    'inputOptions' => [
        'placeholder' => $loginForm->getAttributeLabel('email'),
    ],
];

$passwordFieldOptions = [
    'inputOptions' => [
        'placeholder' => $loginForm->getAttributeLabel('password'),
    ],
];

$captchaFieldOptions = [
    'inputOptions' => [
        'placeholder' => $loginForm->getAttributeLabel('captcha'),
        'class' => 'form-control',
    ],
];

$captchaWidgetOptions = [
    'captchaAction' => '/captcha',
    'template' => '<div class="input-group"><span class="input-group-text">{image}</span> {input}</div>',
    'options' => ['class' => 'form-control'],
];

$checkboxOptions = [
    'template' => '{input} {label}',
    'options' => ['class' => 'form-control'],
];

?>
<div class="user-login">
    <h1>
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="row">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin([
                'id' => 'user-login-form',
                'options' => ['class' => 'form-horizontal'],
                'enableClientValidation' => false,
                'enableAjaxValidation' => true,
            ]); ?>
            <?= $form->field($loginForm, 'email', $emailFieldOptions)->textInput()->label(false) ?>
            <?= $form->field($loginForm, 'password', $passwordFieldOptions)->passwordInput()->label(false) ?>
            <?= $form->field($loginForm, 'captcha', $captchaFieldOptions)->widget(Captcha::class, $captchaWidgetOptions)->label(false) ?>
            <?= $form->field($loginForm, 'rememberMe')->checkbox($checkboxOptions) ?>
            <?= Html::submitButton(UserModule::t('login_form', 'loginButton'),
                ['class' => 'btn btn-success', 'name' => 'user-login-button']
            ) ?>
            <?= Html::a(UserModule::t('registration_form', 'registrationButton'), '/registration',
                ['class' => 'btn btn-light', 'name' => 'user-registration-button']
            ) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
