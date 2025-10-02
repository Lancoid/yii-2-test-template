<?php

declare(strict_types=1);

use app\modules\track\dictionaries\TrackDictionary;
use app\modules\track\forms\TrackUpdateForm;
use app\modules\track\TrackModule;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveForm $form
 * @var TrackUpdateForm $updateForm
 */
$this->title = TrackModule::t('crud_form', 'updateTitle');

$numberFieldOptions = [
    'inputOptions' => [
        'placeholder' => $updateForm->getAttributeLabel('number'),
    ],
];

$statusFieldOptions = [
    'prompt' => $updateForm->getAttributeLabel('choose_status'),
];

?>
<div class="track-update">
    <h1>
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="row">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin([
                'id' => 'track-update-form',
                'options' => ['class' => 'form-horizontal'],
                'enableClientValidation' => false,
                'enableAjaxValidation' => true,
            ]); ?>
            <?= $form->field($updateForm, 'number', $numberFieldOptions)
                ->textInput()
                ->label(false) ?>
            <?= $form->field($updateForm, 'status', $statusFieldOptions)
                ->dropDownList(TrackDictionary::getStatusListDescription(), $statusFieldOptions)
                ->label(false) ?>
            <?= Html::submitButton(TrackModule::t('crud_form', 'updateButton'),
                ['class' => 'btn btn-success', 'name' => 'track-update-button']
            ) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
