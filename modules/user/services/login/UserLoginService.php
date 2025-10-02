<?php

declare(strict_types=1);

namespace app\modules\user\services\login;

use app\modules\core\dictionaries\TimeDurationDictionary;
use app\modules\core\repositories\exceptions\RepositoryNotFoundException;
use app\modules\core\services\exceptions\ServiceFormValidationException;
use app\modules\user\repositories\UserRepositoryInterface;
use app\modules\user\services\login\input\UserLoginInputInterface;
use app\modules\user\services\web\UserWebServiceInterface;
use app\modules\user\UserModule;
use Yii;

readonly class UserLoginService implements UserLoginServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserWebServiceInterface $userWebService,
    ) {}

    public function handle(UserLoginInputInterface $userLoginInput): bool
    {
        $password = trim($userLoginInput->getPassword() ?? '');

        if (empty($password)) {
            throw new ServiceFormValidationException('email', UserModule::t('login_form', 'Incorrect username or password'));
        }

        try {
            $userDto = $this->userRepository->findByEmail($userLoginInput->getEmail() ?? '');
        } catch (RepositoryNotFoundException) {
            throw new ServiceFormValidationException('email', UserModule::t('login_form', 'Incorrect username or password'));
        }

        $isPasswordValidate = Yii::$app->security->validatePassword($password, $userDto->getPasswordHash() ?? '');

        if (!$isPasswordValidate) {
            throw new ServiceFormValidationException('email', UserModule::t('login_form', 'Incorrect username or password'));
        }

        return $this->userWebService->login(
            $userDto,
            $userLoginInput->getRememberMe() ? TimeDurationDictionary::ONE_MONTH : 0
        );
    }
}
