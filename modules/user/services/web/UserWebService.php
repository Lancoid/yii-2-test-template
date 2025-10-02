<?php

declare(strict_types=1);

namespace app\modules\user\services\web;

use app\modules\user\models\User;
use app\modules\user\repositories\UserRepositoryInterface;
use app\modules\user\services\dto\UserDto;
use Yii;

readonly class UserWebService implements UserWebServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function login(UserDto $userDto, int $sessionTimeout = 0): bool
    {
        $appSessionTimeout = Yii::$app->params['userSessionTimeout'] ?: 0;

        if (!is_numeric($appSessionTimeout)) {
            $appSessionTimeout = 0;
        }

        $user = $this->userRepository->fillModel(new User(), $userDto);

        if ($sessionTimeout < 0) {
            $sessionTimeout = 0;
        }

        return Yii::$app->user->login($user, $sessionTimeout !== 0 ? $sessionTimeout : (int)$appSessionTimeout);
    }

    public function getCurrent(): ?UserDto
    {
        /** @var ?User $user */
        $user = Yii::$app->user->getIdentity();

        if (!$user) {
            return null;
        }

        $userDto = $this->userRepository->fillDto($user);
        $userDto->setPasswordHash(null);

        return $userDto;
    }
}
