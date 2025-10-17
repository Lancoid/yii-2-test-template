<?php

declare(strict_types=1);

namespace app\modules\user\models;

use app\modules\user\models\query\UserQuery;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User ActiveRecord model.
 * Represents a user entity and provides identity methods for authentication.
 *
 * @property null|int $id User ID
 * @property null|int $created_at Creation timestamp
 * @property null|int $updated_at Update timestamp
 * @property null|string $username Username
 * @property null|string $password_hash Password hash
 * @property null|string $auth_key Authentication key
 * @property null|string $access_token Access token
 * @property null|string $email Email address
 * @property null|string $phone Phone number
 * @property null|int $status User status
 * @property null|int $agreement_personal_data Agreement to personal data processing
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * Returns the name of the database table associated with this AR class.
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * Returns validation rules for user attributes.
     */
    public function rules(): array
    {
        return [
            [
                [
                    'created_at',
                    'updated_at',
                    'username',
                    'password_hash',
                    'auth_key',
                    'email',
                    'phone',
                    'status',
                    'agreement_personal_data',
                ],
                'required',
            ],
            [
                [
                    'created_at',
                    'updated_at',
                    'status',
                    'agreement_personal_data',
                ],
                'integer',
            ],
            [
                [
                    'username',
                    'password_hash',
                    'email',
                ],
                'string',
                'max' => 255,
            ],
            [
                ['auth_key', 'access_token'],
                'string',
                'max' => 32,
            ],
            [
                ['phone'],
                'string',
                'max' => 12,
            ],
            [['email'], 'unique'],
            [['access_token'], 'unique'],
            [['access_token'], 'default', 'value' => null],
        ];
    }

    /**
     * Returns attribute labels for user fields.
     *
     * @return array<string, string>
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'username' => Yii::t('app', 'Username'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'access_token' => Yii::t('app', 'Access Token'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'status' => Yii::t('app', 'Status'),
            'agreement_personal_data' => Yii::t('app', 'Agreement personal data'),
        ];
    }

    /**
     * Returns a new query object for this class.
     */
    public static function find(): UserQuery
    {
        return new UserQuery();
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param int $id User ID
     */
    public static function findIdentity($id): ?IdentityInterface
    {
        if (!is_int($id)) {
            return null;
        }

        /** @var null|IdentityInterface $result */
        $result = static::find()->byId($id)->one();

        if ($result && !$result instanceof self) {
            return null;
        }

        return $result;
    }

    /**
     * Finds an identity by the given access token.
     *
     * @param string $token Access token
     * @param null|mixed $type Token type (unused)
     */
    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        if (!is_string($token)) {
            return null;
        }

        $token = trim($token);

        /** @var null|IdentityInterface $result */
        $result = static::find()->byAccessToken($token)->one();

        if ($result && !$result instanceof self) {
            return null;
        }

        return $result;
    }

    /**
     * Returns the user ID.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Returns the authentication key.
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * Validates the given authentication key.
     *
     * @param string $authKey Authentication key to validate
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }
}
