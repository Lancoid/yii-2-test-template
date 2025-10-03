<?php

declare(strict_types=1);

use app\modules\track\services\dto\TrackDto;
use app\modules\track\TrackModule;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var View $this
 * @var TrackDto $trackDto
 */
$template = '<tr><th class="col-md-4">{label}</th><td class="col-md-8">{value}</td></tr>';
?>

<p>
    <?= Html::a('Обновить', ['update', 'id' => $trackDto->getId()], ['class' => 'btn btn-success']) ?>
    <?= Html::a('Удалить', ['delete', 'id' => $trackDto->getId()], ['class' => 'btn btn-warning']) ?>
</p>

<div class="row">
    <div class="col-md-12 col-lg-6">
        <?= DetailView::widget([
            'model' => [],
            'template' => $template,
            'attributes' => [
                [
                    'label' => TrackModule::t('crud_form', 'id'),
                    'value' => $trackDto->getId(),
                ],
                [
                    'label' => TrackModule::t('crud_form', 'number'),
                    'value' => $trackDto->getNumber(),
                ],
                [
                    'label' => TrackModule::t('crud_form', 'status'),
                    'value' => $trackDto->getStatus(),
                ],
                [
                    'label' => TrackModule::t('crud_form', 'created_at'),
                    'value' => DateTimeImmutable::createFromTimestamp($trackDto->getCreatedAt() ?? 0)->format('Y-m-d H:i:s'),
                ],
                [
                    'label' => TrackModule::t('crud_form', 'updated_at'),
                    'value' => DateTimeImmutable::createFromTimestamp($trackDto->getUpdatedAt() ?? 0)->format('Y-m-d H:i:s'),
                ],
            ],
        ]) ?>
    </div>
</div>
