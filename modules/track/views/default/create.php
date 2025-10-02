<?php

declare(strict_types=1);

use app\modules\track\dictionaries\TrackDictionary;
use app\modules\track\forms\TrackCreateForm;
use app\modules\track\TrackModule;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveForm $form
 * @var TrackCreateForm $createForm
 */
$this->title = TrackModule::t('crud_form', 'createTitle');

$numberFieldOptions = [
    'inputOptions' => [
        'placeholder' => $createForm->getAttributeLabel('number'),
    ],
];

$statusFieldOptions = [
    'prompt' => $createForm->getAttributeLabel('choose_status'),
];

?>
<div class="track-create">
    <h1>
        <?= Html::encode($this->title) ?>
    </h1>
    <div class="row">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin([
                'id' => 'track-create-form',
                'options' => ['class' => 'form-horizontal'],
                'enableClientValidation' => false,
                'enableAjaxValidation' => true,
            ]); ?>
            <?= $form->field($createForm, 'number', $numberFieldOptions)
                ->textInput()
                ->label(false) ?>
            <?= $form->field($createForm, 'status')
                ->dropDownList(TrackDictionary::getStatusListDescription(), $statusFieldOptions)
                ->label(false) ?>
            <?= Html::submitButton(TrackModule::t('crud_form', 'createButton'),
                ['class' => 'btn btn-success', 'name' => 'track-create-button']
            ) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
