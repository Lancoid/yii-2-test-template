<?php

declare(strict_types=1);

namespace app\modules\user\services\create\hydrate;

use app\modules\user\services\create\input\UserCreateInputInterface;
use app\modules\user\services\dto\UserDto;
use DateTimeImmutable;
use Yii;
use yii\base\Exception;

class HydrateCreateUserDto
{
    /**
     * @throws Exception
     */
    public function hydrate(UserCreateInputInterface $userCreateInput): UserDto
    {
        $dateTime = new DateTimeImmutable();

        return new UserDto()
            ->setCreatedAt($dateTime->getTimestamp())
            ->setUpdatedAt($dateTime->getTimestamp())
            ->setUsername($userCreateInput->getUsername())
            ->setPasswordHash(Yii::$app->security->generatePasswordHash($userCreateInput->getPassword() ?? ''))
            ->setAuthKey(Yii::$app->security->generateRandomString())
            ->setAccessToken(md5(Yii::$app->security->generateRandomString()))
            ->setEmail($userCreateInput->getEmail())
            ->setPhone($userCreateInput->getPhone())
            ->setStatus($userCreateInput->getStatus())
            ->setAgreementPersonalData($userCreateInput->getAgreementPersonalData());
    }
}
