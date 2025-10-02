<?php

declare(strict_types=1);

namespace app\modules\user\models\query;

use app\modules\user\models\User;
use yii\db\ActiveQuery;

/**
 * @extends ActiveQuery<User>
 */
class UserQuery extends ActiveQuery
{
    private const string TABLE_ALIAS = 'users';

    public function __construct($config = [])
    {
        parent::__construct(User::class, $config);
    }

    /**
     * @param null|mixed $db
     *
     * @return array<User>
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * @param null|mixed $db
     *
     * @return null|array<string, mixed>|User
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

    public function byUsername(string $username, string $alias = self::TABLE_ALIAS): self
    {
        return $this->andWhere([$alias . '.username' => $username]);
    }

    public function byAccessToken(string $accessToken, string $alias = self::TABLE_ALIAS): self
    {
        return $this->andWhere([$alias . '.access_token' => $accessToken]);
    }

    public function byEmail(string $email, string $alias = self::TABLE_ALIAS): self
    {
        return $this->andWhere([$alias . '.email' => $email]);
    }

    public function byStatus(int $status, string $alias = self::TABLE_ALIAS): self
    {
        return $this->andWhere([$alias . '.status' => $status]);
    }
}
