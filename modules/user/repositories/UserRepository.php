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

class UserRepository implements UserRepositoryInterface
{
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

    public function findById(int $id): UserDto
    {
        $user = $this->findModelId($id);

        return $this->fillDto($user);
    }

    public function existById(int $id): bool
    {
        return User::find()->byId($id)->exists();
    }

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

    private function findModelId(int $id): User
    {
        if (!$model = User::findOne($id)) {
            throw new RepositoryNotFoundException(User::class);
        }

        return $model;
    }
}
