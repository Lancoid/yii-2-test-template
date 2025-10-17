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
            $this->handleException(
                new Exception('UserDataProvider::getOne ' . $throwable->getMessage(), $throwable->getCode(), $throwable),
                [
                    'user_id' => $userId,
                ]
            );
        }

        return null;
    }

    public function existByEmail(string $email, ?int $notId = null): bool
    {
        try {
            return $this->userRepository->existByEmail($email, $notId);
        } catch (Throwable $throwable) {
            $this->handleException(
                new Exception('UserDataProvider::existByEmail ' . $throwable->getMessage(), $throwable->getCode(), $throwable),
                [
                    'email' => $email,
                    'not_id' => $notId,
                ]
            );
        }

        return false;
    }

    /**
     * Handles exceptions and reports them to Sentry.
     *
     * @param array<string, mixed> $context Additional context information to include in the Sentry event
     */
    private function handleException(Exception $exception, array $context): void
    {
        $this->sentryService->captureException($exception, array_merge($context, [
            'error_message' => $exception->getMessage(),
        ]));
    }
}
