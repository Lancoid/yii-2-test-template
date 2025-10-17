<?php

declare(strict_types=1);

namespace app\modules\user\services\create\hydrate;

use app\modules\user\services\create\input\UserCreateInputInterface;
use app\modules\user\services\dto\UserDto;
use DateTimeImmutable;
use Yii;
use yii\base\Exception;

/**
 * Service for hydrating UserDto from UserCreateInputInterface.
 * Generates required fields and hashes password.
 */
class HydrateCreateUserDto
{
    /**
     * Creates and hydrates a UserDto from input data.
     *
     * @param UserCreateInputInterface $userCreateInput input data for user creation
     *
     * @return UserDto hydrated user data transfer object
     *
     * @throws Exception if password generation fails
     */
    public function hydrate(UserCreateInputInterface $userCreateInput): UserDto
    {
        $dateTime = new DateTimeImmutable();
        $password = $userCreateInput->getPassword();

        if (null === $password || '' === $password) {
            throw new Exception('Password cannot be empty.');
        }

        return (new UserDto())
            ->setCreatedAt($dateTime->getTimestamp())
            ->setUpdatedAt($dateTime->getTimestamp())
            ->setUsername($userCreateInput->getUsername())
            ->setPasswordHash(Yii::$app->security->generatePasswordHash($password))
            ->setAuthKey(Yii::$app->security->generateRandomString())
            ->setAccessToken(md5(Yii::$app->security->generateRandomString()))
            ->setEmail($userCreateInput->getEmail())
            ->setPhone($userCreateInput->getPhone())
            ->setStatus($userCreateInput->getStatus())
            ->setAgreementPersonalData($userCreateInput->getAgreementPersonalData());
    }
}
