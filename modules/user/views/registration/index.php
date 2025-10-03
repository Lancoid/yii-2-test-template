<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use app\modules\user\assets\registration\UserRegistrationAsset;
use app\modules\user\forms\UserRegistrationForm;
use app\modules\user\UserModule;
use juliardi\captcha\Captcha;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveForm $form
 * @var UserRegistrationForm $registrationForm
 */
$this->title = UserModule::t('registration_form', 'registrationTitle');

$usernameFieldOptions = [
    'inputOptions' => [
        'placeholder' => $registrationForm->getAttributeLabel('username'),
    ],
];

$passwordFieldOptions = [
    'inputOptions' => [
        'placeholder' => $registrationForm->getAttributeLabel('password'),
    ],
];

$emailFieldOptions = [
    'inputOptions' => [
        'placeholder' => $registrationForm->getAttributeLabel('email'),
    ],
];

$phoneFieldOptions = [
    'inputOptions' => [
        'placeholder' => $registrationForm->getAttributeLabel('phone'),
    ],
];

$captchaFieldOptions = [
    'inputOptions' => [
        'placeholder' => $registrationForm->getAttributeLabel('captcha'),
        'class' => 'form-control',
    ],
];

$captchaWidgetOptions = [
    'captchaAction' => '/captcha',
    'template' => '<div class="input-group"><span class="input-group-text">{image}</span> {input}</div>',
    'options' => ['class' => 'form-control'],
];

$checkboxOptions = [
    'template' => '{input} {label} {error}',
    'options' => ['class' => 'form-control'],
];

UserRegistrationAsset::register($this);

?>
<div class="user-registration">
    <h1>
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="row">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin([
                'id' => 'user-registration-form',
                'options' => ['class' => 'form-horizontal'],
                'enableClientValidation' => false,
                'enableAjaxValidation' => true,
            ]); ?>
            <?= $form->field($registrationForm, 'username', $usernameFieldOptions)->textInput()->label(false) ?>
            <?= $form->field($registrationForm, 'password', $passwordFieldOptions)->textInput()->label(false) ?>
            <?= $form->field($registrationForm, 'email', $emailFieldOptions)->textInput()->label(false) ?>
            <?= $form->field($registrationForm, 'phone', $phoneFieldOptions)->textInput()->label(false) ?>
            <?= $form->field($registrationForm, 'captcha', $captchaFieldOptions)->widget(Captcha::class, $captchaWidgetOptions)->label(false) ?>
            <?= $form->field($registrationForm, 'agreementPersonalData')->checkbox($checkboxOptions) ?>
            <?= Html::submitButton(UserModule::t('registration_form', 'registrationButton'),
                ['class' => 'btn btn-success', 'name' => 'user-registration-button']
            ) ?>
            <?= Html::a(UserModule::t('login_form', 'loginButton'), '/login',
                ['class' => 'btn btn-light', 'name' => 'user-login-button']
            ) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
