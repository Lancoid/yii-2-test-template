<?php

declare(strict_types=1);

namespace app\modules\user\services\login;

use app\modules\core\services\exceptions\ServiceFormValidationException;
use app\modules\user\services\login\input\UserLoginInputInterface;
use Throwable;
use yii\db\Exception;

interface UserLoginServiceInterface
{
    /**
     * @throws Throwable
     * @throws Exception
     * @throws ServiceFormValidationException
     */
    public function handle(UserLoginInputInterface $userLoginInput): bool;
}
