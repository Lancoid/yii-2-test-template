<?php

declare(strict_types=1);

namespace app\modules\user\repositories;

use app\modules\core\repositories\exceptions\RepositoryActionException;
use app\modules\core\repositories\exceptions\RepositoryNotFoundException;
use app\modules\core\repositories\exceptions\RepositorySaveException;
use app\modules\user\models\User;
use app\modules\user\services\dto\UserDto;
use RuntimeException;
use Throwable;

/**
 * Repository for user persistence and retrieval.
 * Implements methods for saving, finding, and checking users.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Saves a user DTO to the storage.
     *
     * @param UserDto $userDto user data transfer object
     *
     * @return int saved user ID
     *
     * @throws RepositorySaveException if saving fails
     */
    public function save(UserDto $userDto): int
    {
        $model = !$userDto->getId()
            ? new User()
            : $this->findModelId($userDto->getId());

        $model = $this->fillModel($model, $userDto);

        if ($model->isNewRecord) {
            unset($model->id);
        }

        try {
            if (!$model->save(false)) {
                throw new RuntimeException('Saving error.');
            }

            if (!$model->id) {
                throw new RuntimeException('Saving error.');
            }
        } catch (Throwable $exception) {
            throw new RepositorySaveException('User - Saving error.', (int)$exception->getCode(), $exception);
        }

        return $model->id;
    }

    /**
     * Finds a user by ID.
     *
     * @param int $id user ID
     *
     * @return UserDto user data transfer object
     *
     * @throws RepositoryNotFoundException if user not found
     */
    public function findById(int $id): UserDto
    {
        $user = $this->findModelId($id);

        return $this->fillDto($user);
    }

    /**
     * Checks if a user exists by ID.
     *
     * @param int $id user ID
     *
     * @return bool true if user exists, false otherwise
     */
    public function existById(int $id): bool
    {
        return User::find()->byId($id)->exists();
    }

    /**
     * Finds a user by access token.
     *
     * @param string $accessToken access token
     *
     * @return UserDto user data transfer object
     *
     * @throws RepositoryActionException if token is empty
     * @throws RepositoryNotFoundException if user not found
     */
    public function findByAccessToken(string $accessToken): UserDto
    {
        $accessToken = trim($accessToken);

        if ('' === $accessToken || '0' === $accessToken) {
            throw new RepositoryActionException('Argument accessToken is empty.');
        }

        /** @var ?User $model */
        $model = User::find()->byAccessToken($accessToken)->one();

        if (!$model) {
            throw new RepositoryNotFoundException(User::class);
        }

        return $this->fillDto($model);
    }

    /**
     * Fills a User model with data from a UserDto.
     *
     * @param User $user user model
     * @param UserDto $userDto user data transfer object
     *
     * @return User filled user model
     */
    public function fillModel(User $user, UserDto $userDto): User
    {
        $user->id = $userDto->getId();
        $user->created_at = $userDto->getCreatedAt();
        $user->updated_at = $userDto->getUpdatedAt();
        $user->username = $userDto->getUsername();
        $user->password_hash = $userDto->getPasswordHash();
        $user->auth_key = $userDto->getAuthKey();
        $user->access_token = $userDto->getAccessToken();
        $user->email = $userDto->getEmail();
        $user->phone = $userDto->getPhone();
        $user->status = $userDto->getStatus();
        $user->agreement_personal_data = $userDto->getAgreementPersonalData();

        return $user;
    }

    /**
     * Creates a UserDto from a User model.
     *
     * @param User $user user model
     *
     * @return UserDto user data transfer object
     */
    public function fillDto(User $user): UserDto
    {
        $userDto = new UserDto();

        $userDto->setId($user->id)
            ->setCreatedAt($user->created_at)
            ->setUpdatedAt($user->updated_at)
            ->setUsername($user->username)
            ->setPasswordHash($user->password_hash)
            ->setAuthKey($user->auth_key)
            ->setAccessToken($user->access_token)
            ->setEmail($user->email)
            ->setPhone($user->phone)
            ->setStatus($user->status)
            ->setAgreementPersonalData($user->agreement_personal_data);

        return $userDto;
    }

    /**
     * Finds a user by email, optionally excluding a user by ID.
     *
     * @param string $email email address
     * @param null|int $notId user ID to exclude
     *
     * @return UserDto user data transfer object
     *
     * @throws RepositoryActionException if email is empty
     * @throws RepositoryNotFoundException if user not found
     */
    public function findByEmail(string $email, ?int $notId = null): UserDto
    {
        $email = trim($email);

        if (!$email) {
            throw new RepositoryActionException('Argument email is empty.');
        }

        $userQuery = User::find()
            ->byEmail($email);

        if (null !== $notId && 0 !== $notId) {
            $userQuery->byNotId($notId);
        }

        $model = $userQuery->one();

        if (!$model instanceof User) {
            throw new RepositoryNotFoundException(User::class);
        }

        return $this->fillDto($model);
    }

    /**
     * Checks if a user exists by email, optionally excluding a user by ID.
     *
     * @param string $email email address
     * @param null|int $notId user ID to exclude
     *
     * @return bool true if user exists, false otherwise
     *
     * @throws RepositoryActionException if email is empty
     */
    public function existByEmail(string $email, ?int $notId = null): bool
    {
        $email = trim($email);

        if (!$email) {
            throw new RepositoryActionException('Argument email is empty.');
        }

        $userQuery = User::find()
            ->byEmail($email);

        if (null !== $notId && 0 !== $notId) {
            $userQuery->byNotId($notId);
        }

        return $userQuery->exists();
    }

    /**
     * Finds a User model by ID.
     *
     * @param int $id user ID
     *
     * @return User user model
     *
     * @throws RepositoryNotFoundException if user not found
     */
    private function findModelId(int $id): User
    {
        $model = User::findOne($id);

        if (!$model) {
            throw new RepositoryNotFoundException(User::class);
        }

        return $model;
    }
}
