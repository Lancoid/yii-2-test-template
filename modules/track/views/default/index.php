<?php

declare(strict_types=1);

use app\modules\core\dataProviders\DtoDataProvider;
use app\modules\core\widgets\gridView\GridView;
use app\modules\track\dictionaries\TrackDictionary;
use app\modules\track\forms\TrackSearchForm;
use app\modules\track\services\dto\TrackDto;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;

/**
 * @var DtoDataProvider $dataProvider
 * @var TrackSearchForm $searchModel
 */
?>

<div class="body-content">
    <div class="row">
        <div class="col-md-10 offset-1">
            <?= Html::a('Добавить новый трек', ['create'], ['class' => 'btn btn-primary mb-3']) ?>
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'id' => 'track-grid',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'number',
                    'format' => 'raw',
                    'value' => static function (TrackDto $trackDto) {
                        return Html::a($trackDto->getNumber() ?? '', ['update', 'id' => $trackDto->getId()], ['data-pjax' => 0]);
                    },
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'filter' => Html::activeDropDownList($searchModel, 'status', TrackDictionary::getStatusListDescription(), ['class' => 'form-control', 'prompt' => 'Все']),
                    'value' => static function (TrackDto $trackDto) {
                        return TrackDictionary::getStatusListDescription()[$trackDto->getStatus()] ?? $trackDto->getStatus();
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'format' => 'datetime',
                    'filter' => false,
                    'value' => static function (TrackDto $trackDto): ?int {
                        return $trackDto->getCreatedAt();
                    },
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => 'datetime',
                    'filter' => false,
                    'value' => static function (TrackDto $trackDto): ?int {
                        return $trackDto->getUpdatedAt();
                    },
                ],
            ],
        ]) ?>
        <?php Pjax::end(); ?>
    </div>
</div>
</div>
