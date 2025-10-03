<?php

declare(strict_types=1);

namespace app\modules\user\dataProviders;

use app\modules\core\services\sentry\SentryServiceInterface;
use app\modules\user\repositories\UserRepositoryInterface;
use app\modules\user\services\dto\UserDto;
use Exception;
use Throwable;

readonly class UserDataProvider implements UserDataProviderInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private SentryServiceInterface $sentryService
    ) {}

    public function getOne(int $userId): ?UserDto
    {
        try {
            return $this->userRepository->findById($userId);
        } catch (Throwable $throwable) {
            $exception = new Exception('UserDataProvider::getOne ' . $throwable->getMessage(), $throwable->getCode(), $throwable);

            $this->sentryService->captureException($exception, [
                'user_id' => $userId,
                'error_message' => $exception->getMessage(),
            ]);
        }

        return null;
    }

    public function existByEmail(string $email, ?int $notId = null): bool
    {
        try {
            return $this->userRepository->existByEmail($email, $notId);
        } catch (Throwable $throwable) {
            $exception = new Exception('UserDataProvider::existByEmail ' . $throwable->getMessage(), $throwable->getCode(), $throwable);

            $this->sentryService->captureException($exception, [
                'email' => $email,
                'not_id' => $notId,
                'error_message' => $throwable->getMessage(),
            ]);
        }

        return true;
    }
}
