<?php

declare(strict_types=1);

namespace app\modules\user\models\query;

use app\modules\user\models\User;
use yii\db\ActiveQuery;

/**
 * Custom query class for User model.
 * Provides convenient methods for filtering user records.
 *
 * @extends ActiveQuery<User>
 */
class UserQuery extends ActiveQuery
{
    /**
     * Default table alias for user queries.
     */
    public const string TABLE_ALIAS = 'users';

    /**
     * UserQuery constructor.
     *
     * @param array $config query configuration
     */
    public function __construct($config = [])
    {
        parent::__construct(User::class, $config);
    }

    /**
     * Returns all user records matching the query.
     *
     * @param null|mixed $db database connection
     *
     * @return array<User> list of User models
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * Returns a single user record matching the query.
     *
     * @param null|mixed $db database connection
     *
     * @return null|array<string, mixed>|User user model, array or null
     */
    public function one($db = null): mixed
    {
        return parent::one($db);
    }

    /**
     * Filters query by user ID.
     *
     * @param int $userId user ID
     * @param string $alias table alias
     */
    public function byId(int $userId, string $alias = self::TABLE_ALIAS): self
    {
        return $this->andWhere([$alias . '.id' => $userId]);
    }

    /**
     * Filters query by excluding user ID.
     *
     * @param int $userId user ID to exclude
     * @param string $alias table alias
     */
    public function byNotId(int $userId, string $alias = self::TABLE_ALIAS): self
    {
        return $this->andWhere(['NOT', [$alias . '.id' => $userId]]);
    }

    /**
     * Filters query by username.
     *
     * @param string $username username
     * @param string $alias table alias
     */
    public function byUsername(string $username, string $alias = self::TABLE_ALIAS): self
    {
        return $this->andWhere([$alias . '.username' => $username]);
    }

    /**
     * Filters query by access token.
     *
     * @param string $accessToken access token
     * @param string $alias table alias
     */
    public function byAccessToken(string $accessToken, string $alias = self::TABLE_ALIAS): self
    {
        return $this->andWhere([$alias . '.access_token' => $accessToken]);
    }

    /**
     * Filters query by email.
     *
     * @param string $email email address
     * @param string $alias table alias
     */
    public function byEmail(string $email, string $alias = self::TABLE_ALIAS): self
    {
        return $this->andWhere([$alias . '.email' => $email]);
    }

    /**
     * Filters query by user status.
     *
     * @param int $status user status
     * @param string $alias table alias
     */
    public function byStatus(int $status, string $alias = self::TABLE_ALIAS): self
    {
        return $this->andWhere([$alias . '.status' => $status]);
    }
}
