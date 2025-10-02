<?php

declare(strict_types=1);

namespace app\modules\track\models;

use app\modules\track\models\query\TrackQuery;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property ?int $id
 * @property ?int $created_at
 * @property ?int $updated_at
 * @property ?string $number
 * @property ?string $status
 */
class Track extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'track';
    }

    public function rules(): array
    {
        return [
            [['created_at', 'updated_at', 'number', 'status'], 'required'],

            [['status'], 'integer'],

            [['created_at', 'updated_at'], 'integer'],
            [['number'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 32],

            [['number'], 'unique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'number' => Yii::t('app', 'Number'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public static function find(): TrackQuery
    {
        return new TrackQuery();
    }
}
