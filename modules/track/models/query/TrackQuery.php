<?php

declare(strict_types=1);

namespace app\modules\track\models\query;

use app\modules\track\models\Track;
use yii\db\ActiveQuery;

/**
 * @extends ActiveQuery<Track>
 */
class TrackQuery extends ActiveQuery
{
    private const string TABLE_ALIAS = 'track';

    public function __construct($config = [])
    {
        parent::__construct(Track::class, $config);
    }

    /**
     * @param null|mixed $db
     *
     * @return array<Track>
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * @param null|mixed $db
     *
     * @return null|array<string, mixed>|Track
     */
    public function one($db = null): mixed
    {
        return parent::one($db);
    }

    public function byId(int $userId, string $alias = self::TABLE_ALIAS): self
    {
        return $this->andWhere([$alias . '.id' => $userId]);
    }

    public function byNotId(int $userId, string $alias = self::TABLE_ALIAS): self
    {
        return $this->andWhere(['NOT', [$alias . '.id' => $userId]]);
    }

    public function byNumber(string $number, string $alias = self::TABLE_ALIAS): self
    {
        return $this->andWhere([$alias . '.number' => $number]);
    }

    public function byStatus(string $status, string $alias = self::TABLE_ALIAS): self
    {
        return $this->andWhere([$alias . '.status' => $status]);
    }
}
