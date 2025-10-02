<?php

declare(strict_types=1);

namespace app\modules\user\models;

use app\modules\user\models\query\UserQuery;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property ?int $id
 * @property ?int $created_at
 * @property ?int $updated_at
 * @property ?string $username
 * @property ?string $password_hash
 * @property ?string $auth_key
 * @property ?string $access_token
 * @property ?string $email
 * @property ?string $phone
 * @property ?int $status
 */
class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName(): string
    {
        return 'users';
    }

    public function rules(): array
    {
        return [
            [['access_token'], 'default', 'value' => null],
            [['created_at', 'updated_at', 'username', 'password_hash', 'auth_key', 'email', 'phone', 'status'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
            [['username', 'password_hash', 'access_token', 'email'], 'string', 'max' => 255],
            [['auth_key', 'phone'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['access_token'], 'unique'],
        ];
    }

    /**
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
        ];
    }

    public static function find(): UserQuery
    {
        return new UserQuery();
    }

    public static function findIdentity($id): ?IdentityInterface
    {
        if (!is_int($id)) {
            return null;
        }

        /** @var ?IdentityInterface $result */
        $result = static::find()->byId($id)->one();

        if ($result && !$result instanceof self) {
            return null;
        }

        return $result;
    }

    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        if (!is_string($token)) {
            return null;
        }

        $token = trim($token);

        /** @var ?IdentityInterface $result */
        $result = static::find()->byAccessToken($token)->one();

        if ($result && !$result instanceof self) {
            return null;
        }

        return $result;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }
}
